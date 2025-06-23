<?php

namespace App\Services;

use App\Http\Controllers\Admin\FCMController as AdminFCMController;
use App\Models\Driver;
use App\Models\Order;
use App\Models\ServicePayment;
use App\Models\Service;
use App\Http\Controllers\FCMController;
use Illuminate\Support\Facades\DB;
use Kreait\Firebase\Firestore;


class DriverLocationService
{
    protected $firestore;
    
    public function __construct(Firestore $firestore)
    {
        $this->firestore = $firestore;
    }
    
    public function findAndStoreOrderInFirebase($userLat, $userLng, $orderId, $serviceId, $radius = 10, $orderStatus = 'pending')
    {
        try {
            // Step 1: Get available drivers from MySQL filtered by service
            $availableDriverIds = $this->getAvailableDriversForService($serviceId);
            
            if (empty($availableDriverIds)) {
                return [
                    'success' => false,
                    'message' => 'No available drivers found for this service'
                ];
            }
            
            // Step 2: Get driver locations from Firestore
            $driversWithLocations = $this->getDriverLocationsFromFirestore($availableDriverIds);
            
            if (empty($driversWithLocations)) {
                return [
                    'success' => false,
                    'message' => 'No drivers with active locations found for this service'
                ];
            }
            
            // Step 3: Calculate distances and sort
            $sortedDrivers = $this->sortDriversByDistance($driversWithLocations, $userLat, $userLng, $radius);
            
            if (empty($sortedDrivers)) {
                return [
                    'success' => false,
                    'message' => 'No drivers found within specified radius for this service'
                ];
            }
            
            // Step 4: Write order data to Firebase instead of sending notifications
            $firebaseResult = $this->writeOrderToFirebase($orderId, $sortedDrivers, $serviceId, $orderStatus);
            
            return [
                'success' => $firebaseResult['success'],
                'drivers_found' => count($sortedDrivers),
                'drivers' => $sortedDrivers,
                'service_id' => $serviceId,
                'firebase_write' => $firebaseResult['success'] ? 'success' : 'failed',
                'message' => $firebaseResult['message'] ?? 'Order data processed'
            ];
            
        } catch (\Exception $e) {
            \Log::error('Error in findAndStoreOrderInFirebase: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error processing request: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get available drivers from MySQL filtered by service
     */
    private function getAvailableDriversForService($serviceId)
    {
        return Driver::where('status', 1) // status = 1 (online)
            ->where('activate', 1) // activate = 1 (active)
            ->whereNotIn('id', function($query) {
                $query->select('driver_id')
                    ->from('orders')
                    ->whereIn('status', [1, 2, 3]) // Pending, Accepted, On the way
                    ->whereNotNull('driver_id');
            })
            // Filter drivers who can provide this service
            ->whereHas('services', function($query) use ($serviceId) {
                $query->where('service_id', $serviceId);
            })
            ->pluck('id')
            ->toArray();
    }
    
    /**
     * Get driver locations from Firestore
     */
    private function getDriverLocationsFromFirestore(array $driverIds)
    {
        $driversWithLocations = [];
        
        try {
            $collection = $this->firestore->database()->collection('drivers');
            
            foreach ($driverIds as $driverId) {
                $document = $collection->document((string)$driverId)->snapshot();
                
                if ($document->exists()) {
                    $data = $document->data();
                    
                    // Check if location data exists
                    if (isset($data['lat']) && isset($data['lng']) && 
                        !empty($data['lat']) && !empty($data['lng'])) {
                        
                        $driversWithLocations[] = [
                            'id' => $driverId,
                            'lat' => (float)$data['lat'],
                            'lng' => (float)$data['lng'],
                        ];
                    }
                }
            }
            
        } catch (\Exception $e) {
            \Log::error('Error fetching from Firestore: ' . $e->getMessage());
        }
        
        return $driversWithLocations;
    }
    
    /**
     * Sort drivers by distance from user location
     */
    private function sortDriversByDistance(array $drivers, $userLat, $userLng, $maxRadius)
    {
        $driversWithDistance = [];
        
        foreach ($drivers as $driver) {
            $distance = $this->calculateDistance(
                $userLat, 
                $userLng, 
                $driver['lat'], 
                $driver['lng']
            );
            
            // Only include drivers within the specified radius
            if ($distance <= $maxRadius) {
                $driver['distance'] = round($distance, 2);
                $driversWithDistance[] = $driver;
            }
        }
        
        // Sort by distance (nearest first)
        usort($driversWithDistance, function($a, $b) {
            return $a['distance'] <=> $b['distance'];
        });
        
        return $driversWithDistance;
    }
    
    /**
     * Calculate distance between two coordinates using Haversine formula
     */
    private function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371; // Earth's radius in kilometers
        
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        
        $a = sin($dLat/2) * sin($dLat/2) + 
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * 
             sin($dLng/2) * sin($dLng/2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $distance = $earthRadius * $c;
        
        return $distance;
    }
    
    /**
     * Write order data to Firebase orders collection
     */
    private function writeOrderToFirebase($orderId, array $drivers, $serviceId, $orderStatus)
    {
        try {
            // Extract only driver IDs from the sorted drivers array
            $driverIDs = array_map(function($driver) {
                return $driver['id'];
            }, $drivers);
            
            // Prepare order data
            $orderData = [
                'driverIDs' => $driverIDs,
                'status' => $orderStatus,
                'service_id' => $serviceId,
                'created_at' => new \DateTime(),
                'updated_at' => new \DateTime(),
                'total_available_drivers' => count($driverIDs)
            ];
            
            // Write to Firebase orders collection
            $ordersCollection = $this->firestore->database()->collection('orders');
            $ordersCollection->document((string)$orderId)->set($orderData);
            
            \Log::info("Order {$orderId} written to Firebase with " . count($driverIDs) . " available drivers for service {$serviceId}");
            
            return [
                'success' => true,
                'message' => 'Order data successfully written to Firebase',
                'drivers_count' => count($driverIDs)
            ];
            
        } catch (\Exception $e) {
            \Log::error("Error writing order {$orderId} to Firebase: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to write order data to Firebase: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Update order status in Firebase (helper method) not use
     */
    public function updateOrderStatus($orderId, $newStatus)
    {
        try {
            $orderDoc = $this->firestore->database()
                ->collection('orders')
                ->document((string)$orderId);
            
            $orderDoc->update([
                'status' => $newStatus,
                'updated_at' => new \DateTime()
            ]);
            
            \Log::info("Order {$orderId} status updated to {$newStatus} in Firebase");
            
            return [
                'success' => true,
                'message' => 'Order status updated successfully'
            ];
            
        } catch (\Exception $e) {
            \Log::error("Error updating order {$orderId} status in Firebase: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to update order status: ' . $e->getMessage()
            ];
        }
    }
}