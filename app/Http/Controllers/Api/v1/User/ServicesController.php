<?php


namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Setting;
use App\Traits\Responses;
use Illuminate\Http\Request;

class ServicesController extends Controller
{ 
    use Responses;
    
   public function index(Request $request)
{
    // Validate coordinates from the request
    $request->validate([
        'start_lat' => 'required|numeric',
        'start_lng' => 'required|numeric',
        'end_lat'   => 'nullable|numeric',
        'end_lng'   => 'nullable|numeric',
    ]);

    $startLat = $request->start_lat;
    $startLng = $request->start_lng;
    $endLat   = $request->end_lat;
    $endLng   = $request->end_lng;

    $distance = 0;

    // Only calculate distance if both end_lat and end_lng are present
    if (!is_null($endLat) && !is_null($endLng)) {
        $distance = $this->calculateDistance($startLat, $startLng, $endLat, $endLng); // in KM
    }

    $services = Service::where('activate', 1)
        ->whereHas('driverServices', function ($query) {
            $query->where('status', 1); // Only active driver services
        })
        ->with(['servicePayments', 'driverServices' => function ($query) {
            $query->where('status', 1);
        }])
        ->get();

    $data = $services->map(function ($service) use ($distance) {
        $price = $service->start_price + ($service->price_per_km * $distance);

        $serviceData = $service->toArray();
        $serviceData['distance_km'] = round($distance, 2);
        $serviceData['estimated_price'] = round($price, 2);

        return $serviceData;
    });

    return $this->success_response('Services retrieved with full data and estimated prices', $data);
}




    private function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371; // Radius in kilometers

        $lat1 = deg2rad($lat1);
        $lng1 = deg2rad($lng1);
        $lat2 = deg2rad($lat2);
        $lng2 = deg2rad($lng2);

        $latDelta = $lat2 - $lat1;
        $lngDelta = $lng2 - $lng1;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($lat1) * cos($lat2) * pow(sin($lngDelta / 2), 2)));

        return $earthRadius * $angle;
    }




}
