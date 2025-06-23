<?php
namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use App\Traits\Responses;
use App\Models\ServicePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Services\DriverLocationService;
use App\Services\EnhancedFCMService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Admin\FCMController as AdminFCMController;

class OrderController extends Controller
{
    use Responses;

 protected $driverLocationService;
    
    public function __construct(DriverLocationService $driverLocationService)
    {
        $this->driverLocationService = $driverLocationService;
    }

   public function test_notification($orderId)
    { 
         $order = Order::with('user')->find($orderId);
         $driver = auth()->user();
         $driverId = auth()->user()->id;
         $distance = "10";

          // Customize notification content
        $title = '🚗 طلب توصيل جديد';
        $body = "طلب جديد على بعد {$distance} كم - اضغط للقبول";
        
        // Add order details to notification data
        $orderData = [
            'order_id' => (string)$orderId,
            'driver_id' => (string)$driverId,
            'distance' => (string)$distance,
            'order_number' => $order->number ?? '',
            'user_name' => $order->user->name ?? 'مستخدم',
            'price' => (string)($order->price ?? 0),
            'payment_method' => (string)$order->payment_method,
            'screen' => 'new_order',
            'action' => 'accept_order'
        ];
       
        $success = EnhancedFCMService::sendMessageWithData(
            $title,
            $body,
            $driver->fcm_token,
            $driverId,
            $orderData
        );
           return $this->successResponse('notification sent successfully', [
                'data' => $success ,
            ]);
    }
    /**
     * Create a new order and notify nearest drivers
     */
  public function createOrder(Request $request)
{
    // Validate the request
    $validator = Validator::make($request->all(), [
        'pick_name' => 'required',
        'drop_name' => 'nullable',
        'start_lat' => 'required|numeric',
        'start_lng' => 'required|numeric',
        'end_lat'   => 'nullable|numeric',
        'end_lng'   => 'nullable|numeric',
        'service_id' => 'required|exists:services,id',
        'total_price_before_discount' => 'nullable|numeric|min:0',
        'payment_method' => 'nullable|integer|in:1,2,3', // 1 cash, 2 visa // 3 wallet
    ]);
    
    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422);
    }
    
    try {
        // Validate service exists and is active
        $service = Service::where('id', $request->service_id)
                         ->where('activate', 1)
                         ->first();
                         
        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => 'Service not found or inactive'
            ], 404);
        }
        
        // Validate payment method is supported for this service
        $paymentMethod = $request->payment_method ?? 1;
        $isPaymentSupported = ServicePayment::where('service_id', $request->service_id)
                                          ->where('payment_method', $paymentMethod)
                                          ->exists();
                                          
        if (!$isPaymentSupported) {
            return response()->json([
                'success' => false,
                'message' => 'Payment method not supported for this service'
            ], 400);
        }
        
        $number = Order::generateOrderNumber();
        
        // Calculate price if not provided
        $calculatedPrice = $request->total_price_before_discount;
        if (!$calculatedPrice) {
            $distance = $this->calculateDistance(
                $request->start_lat, 
                $request->start_lng, 
                $request->end_lat, 
                $request->end_lng
            );
            $calculatedPrice = $service->start_price + ($distance * $service->price_per_km);
        }
        
        // Create the order
        $order = Order::create([
            'number' => $number,
            'status' => 1, // Pending
            'total_price_before_discount' => $calculatedPrice,
             'total_price_after_discount' => $calculatedPrice, 
             'net_price_for_driver' => $calculatedPrice, 
             'commision_of_admin'=> 1, 
             
            'payment_method' => $paymentMethod,
            'status_payment' => 2, // Unpaid by default
            'user_id' => auth()->user()->id,
            'service_id' => $request->service_id,
            'pick_lat' => $request->start_lat,
            'pick_lng' => $request->start_lng,
            'pick_name' => $request->pick_name,
            'drop_name' => $request->drop_name,
            'drop_lat' => $request->end_lat,
            'drop_lng' => $request->end_lng,
        ]);
        
        // Find and notify nearest drivers for this specific service
        $result = $this->driverLocationService->findAndNotifyNearestDrivers(
            $request->start_lat,
            $request->start_lng,
            $order->id,
            $request->service_id, // Pass service_id
            $request->radius ?? 10000, // Default 10km radius,
            'pending' // Status of order
        );
        
        if ($result['success']) {
            return response()->json([
                'status' => true,
                'message' => 'Order created and drivers notified successfully',
                'data' => [
                    'order' => $order->load('service'), // Load service relationship
                    'service' => $service,
                    'drivers_notified' => $result['drivers_found'],
                    'notifications_sent' => $result['notifications_sent'],
                    'notifications_failed' => $result['notifications_failed'],
                    'user_location' => [
                        'start_lat' => $request->start_lat,
                        'start_lng' => $request->start_lng,
                        'end_lat' => $request->end_lat,
                        'end_lng' => $request->end_lng
                    ]
                ]
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => $result['message'],
                'data' => [
                    'order' => $order->load('service'),
                    'service' => $service,
                    'user_location' => [
                        'start_lat' => $request->start_lat,
                        'start_lng' => $request->start_lng,
                        'end_lat' => $request->end_lat,
                        'end_lng' => $request->end_lng
                    ]
                ]
            ], 200);
        }
        
    } catch (\Exception $e) {
        \Log::error('Error creating order: ' . $e->getMessage());
        
        return response()->json([
            'status' => false,
            'message' => 'Error creating order: ' . $e->getMessage()
        ], 500);
    }
}

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
     * Display a listing of the user's orders
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $validator = Validator::make($request->all(), [
            'status' => 'sometimes|in:1,2,3,4,5,6,7',
            'payment_status' => 'sometimes|in:1,2',
            'payment_method' => 'sometimes|in:1,2,3',
            'per_page' => 'sometimes|integer|min:5|max:100',
            'sort_by' => 'sometimes|in:date,price',
            'sort_direction' => 'sometimes|in:asc,desc',
            'from_date' => 'sometimes|date_format:Y-m-d',
            'to_date' => 'sometimes|date_format:Y-m-d|after_or_equal:from_date'
        ]);
        
        if ($validator->fails()) {
            return $this->error_response('Validation error', $validator->errors());
        }
        
        $query = Order::where('user_id', $user->id);
        
        // // Filter by status if provided
        // if ($request->has('status')) {
        //     $query->where('status', $request->status);
        // }
        
        // // Filter by payment status if provided
        // if ($request->has('payment_status')) {
        //     $query->where('status_payment', $request->payment_status);
        // }
        
        // // Filter by payment method if provided
        // if ($request->has('payment_method')) {
        //     $query->where('payment_method', $request->payment_method);
        // }
        
        // // Filter by date range if provided
        // if ($request->has('from_date')) {
        //     $query->whereDate('created_at', '>=', $request->from_date);
        // }
        
        // if ($request->has('to_date')) {
        //     $query->whereDate('created_at', '<=', $request->to_date);
        // }
        
        // Apply sorting
        $sortBy = $request->sort_by ?? 'created_at';
        $sortDirection = $request->sort_direction ?? 'desc';
        
        if ($sortBy === 'date') {
            $sortBy = 'created_at';
        } elseif ($sortBy === 'price') {
            $sortBy = 'total_price_after_discount';
        }
        
        $query->orderBy($sortBy, $sortDirection);
        
        // Pagination
        $perPage = $request->per_page ?? 15;
        $orders = $query->with(['driver:id,name,phone,photo', 'service:id,name_en'])->paginate($perPage);
        
        // Transform data to include status text and other helper methods
        $orders->getCollection()->transform(function ($order) {
            $order->status_text = $order->getStatusText();
            $order->payment_method_text = $order->getPaymentMethodText();
            $order->payment_status_text = $order->getPaymentStatusText();
            $order->distance = $order->getDistance();
            return $order;
        });
        
        $responseData = [
            'orders' => $orders,
            'meta' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total()
            ]
        ];
        
        return $this->success_response('Orders retrieved successfully', $responseData);
    }
    
    /**
     * Get active orders (pending, accepted, in progress)
     *
     * @return \Illuminate\Http\Response
     */
    public function activeOrders()
    {
        $user = Auth::user();
        
        $orders = Order::where('user_id', $user->id)
            ->whereIn('status', [1, 2, 3, 4]) // Pending or in progress orders
            ->with(['driver:id,name,phone,photo,fcm_token', 'service:id,name'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Transform data to include status text and other helper methods
        $orders->transform(function ($order) {
            $order->status_text = $order->getStatusText();
            $order->payment_method_text = $order->getPaymentMethodText();
            $order->distance = $order->getDistance();
            return $order;
        });
        
        return $this->success_response('Active orders retrieved successfully', $orders);
    }
    
    /**
     * Get completed orders history
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function completedOrders(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'per_page' => 'sometimes|integer|min:5|max:100',
            'from_date' => 'sometimes|date_format:Y-m-d',
            'to_date' => 'sometimes|date_format:Y-m-d|after_or_equal:from_date'
        ]);
        
        if ($validator->fails()) {
            return $this->error_response('Validation error', $validator->errors());
        }
        
        $query = Order::where('user_id', $user->id)
            ->where('status', 5); // Only completed orders
        
        // Filter by date range if provided
        if ($request->has('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        
        if ($request->has('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        
        // Pagination
        $perPage = $request->per_page ?? 15;
        $orders = $query->with(['driver:id,name,phone,photo', 'service:id,name'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
        
        // Transform data to include status text and other helper methods
        $orders->getCollection()->transform(function ($order) {
            $order->status_text = $order->getStatusText();
            $order->payment_method_text = $order->getPaymentMethodText();
            $order->payment_status_text = $order->getPaymentStatusText();
            $order->distance = $order->getDistance();
            return $order;
        });
        
        $responseData = [
            'orders' => $orders,
            'meta' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total()
            ]
        ];
        
        return $this->success_response('Completed orders retrieved successfully', $responseData);
    }
    
    /**
     * Get cancelled orders
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function cancelledOrders(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'per_page' => 'sometimes|integer|min:5|max:100',
            'cancelled_by' => 'sometimes|in:user,driver,all'
        ]);
        
        if ($validator->fails()) {
            return $this->error_response('Validation error', $validator->errors());
        }
        
        $query = Order::where('user_id', $user->id);
        
        // Filter by who cancelled
        $cancelledBy = $request->cancelled_by ?? 'all';
        if ($cancelledBy === 'user') {
            $query->where('status', 6); // Cancelled by user
        } elseif ($cancelledBy === 'driver') {
            $query->where('status', 7); // Cancelled by driver
        } else {
            $query->whereIn('status', [6, 7]); // All cancelled orders
        }
        
        // Pagination
        $perPage = $request->per_page ?? 15;
        $orders = $query->with(['driver:id,name,phone,photo', 'service:id,name'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
        
        // Transform data to include status text and other helper methods
        $orders->getCollection()->transform(function ($order) {
            $order->status_text = $order->getStatusText();
            $order->payment_method_text = $order->getPaymentMethodText();
            $order->distance = $order->getDistance();
            return $order;
        });
        
        $responseData = [
            'orders' => $orders,
            'meta' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total()
            ]
        ];
        
        return $this->success_response('Cancelled orders retrieved successfully', $responseData);
    }
    
    /**
     * Store a newly created order
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'service_id' => 'required|exists:services,id',
            'pick_name' => 'required|string|max:255',
            'pick_lat' => 'required|numeric',
            'pick_lng' => 'required|numeric',
            'drop_name' => 'required|string|max:255',
            'drop_lat' => 'required|numeric',
            'drop_lng' => 'required|numeric',
            'payment_method' => 'required|in:1,2,3'
        ]);
        
        if ($validator->fails()) {
            return $this->error_response('Validation error', $validator->errors());
        }
        
        // Get service details
        $service = Service::find($request->service_id);
        if (!$service) {
            return $this->error_response('Service not found', null);
        }
        
        $price = $request->total_price_before_discount;
        
        // Apply discount if user has one (this is a placeholder - implement your discount logic)
        $discount = 0;
        $userHasDiscount = false; // Implement your discount checking logic
        
        if ($userHasDiscount) {
            // Apply discount logic
            $discount = 0; // Replace with your discount calculation
        }
        
        $priceAfterDiscount = $price - $discount;
        
        // Calculate commission and driver's net price
        $commissionRate = 0.15; // 15% - adjust based on your business model
        $commissionAmount = $priceAfterDiscount * $commissionRate;
        $driverNetPrice = $priceAfterDiscount - $commissionAmount;
        
        // Check payment method and verify wallet balance if needed
        if ($request->payment_method == 3) { // Wallet payment
            if ($user->balance < $priceAfterDiscount) {
                return $this->error_response('Insufficient wallet balance', [
                    'available_balance' => $user->balance,
                    'required_amount' => $priceAfterDiscount
                ]);
            }
        }
        
        // Create order
        $order = new Order();
        $order->user_id = $user->id;
        $order->service_id = $request->service_id;
        $order->pick_name = $request->pick_name;
        $order->pick_lat = $request->pick_lat;
        $order->pick_lng = $request->pick_lng;
        $order->drop_name = $request->drop_name;
        $order->drop_lat = $request->drop_lat;
        $order->drop_lng = $request->drop_lng;
        $order->total_price_before_discount = $price;
        $order->discount_value = $discount;
        $order->total_price_after_discount = $priceAfterDiscount;
        $order->net_price_for_driver = $driverNetPrice;
        $order->commision_of_admin = $commissionAmount;
        $order->status = 1; // Pending
        $order->payment_method = $request->payment_method;
        $order->status_payment = ($request->payment_method == 3) ? 2 : 1; // If wallet payment, mark as paid
        $order->save();
        
        // If payment method is wallet, deduct amount from user's wallet
        if ($request->payment_method == 3) {
            $user->balance -= $priceAfterDiscount;
            $user->save();
            
            // Create wallet transaction record (if you have a wallet_transactions table)
            // This is a placeholder - implement based on your wallet_transactions table structure
            /* 
            WalletTransaction::create([
                'user_id' => $user->id,
                'amount' => $priceAfterDiscount,
                'type_of_transaction' => 2, // Deduction
                'note' => 'Payment for order #' . $order->id
            ]);
            */
        }
        
        // Add helper attributes
        $order->status_text = $order->getStatusText();
        $order->payment_method_text = $order->getPaymentMethodText();
        $order->payment_status_text = $order->getPaymentStatusText();
        $order->distance = $order->getDistance();
        
        // TODO: Notify nearby drivers about new order
        // Implement your driver notification logic here
        
        return $this->success_response('Order created successfully', $order);
    }
    
    /**
     * Display the specified order
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();
        
        $order = Order::where('id', $id)
            ->where('user_id', $user->id)
            ->with([
                'driver:id,name,phone,photo,fcm_token',
                'service:id,name,description,photo'
            ])
            ->first();
        
        if (!$order) {
            return $this->error_response('Order not found', null);
        }
        
        // Add helper attributes
        $order->status_text = $order->getStatusText();
        $order->payment_method_text = $order->getPaymentMethodText();
        $order->payment_status_text = $order->getPaymentStatusText();
        $order->distance = $order->getDistance();
        $order->discount_percentage = $order->getDiscountPercentage();
        
        return $this->success_response('Order details retrieved successfully', $order);
    }
    
    /**
     * Cancel an order by the user
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function cancelOrder(Request $request, $id)
    {
        $user = Auth::user();
        
        $order = Order::where('id', $id)
            ->where('user_id', $user->id)
            ->first();
        
        if (!$order) {
            return $this->error_response('Order not found', null);
        }
        
        // Check if order can be cancelled
        if (!in_array($order->status, [1, 2])) {
            return $this->error_response('Order cannot be cancelled at this stage', null);
        }
        
        $validator = Validator::make($request->all(), [
            'reason_for_cancel' => 'required|string|max:255'
        ]);
        
        if ($validator->fails()) {
            return $this->error_response('Validation error', $validator->errors());
        }
        
        // Process cancellation
        $order->status = 6; // User cancelled order
        $order->reason_for_cancel = $request->reason_for_cancel;
        $order->save();
        
        // If payment was made through wallet, refund the amount
        if ($order->payment_method == 3 && $order->status_payment == 2) {
            $user->balance += $order->total_price_after_discount;
            $user->save();
            
            // Create wallet transaction record for refund
            // This is a placeholder - implement based on your wallet_transactions table structure
            /* 
            WalletTransaction::create([
                'user_id' => $user->id,
                'amount' => $order->total_price_after_discount,
                'type_of_transaction' => 1, // Addition
                'note' => 'Refund for cancelled order #' . $order->id
            ]);
            */
        }
        
        // Notify driver if already assigned
        if ($order->driver_id) {
            // Implement notification logic to driver about cancellation
            // You can use FCM or other push notification service
        }
        
        $responseData = [
            'order_id' => $order->id,
            'status' => $order->status,
            'status_text' => $order->getStatusText(),
            'cancellation_reason' => $order->reason_for_cancel
        ];
        
        return $this->success_response('Order cancelled successfully', $responseData);
    }
}