<?php
namespace App\Http\Controllers\Api\v1\Driver;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderDriverController extends Controller
{
    /**
     * Display a listing of the driver's orders
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $driver = Auth::guard('driver-api')->user();
        
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
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $query = Order::where('driver_id', $driver->id);
        
        // Filter by status if provided
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by payment status if provided
        if ($request->has('payment_status')) {
            $query->where('status_payment', $request->payment_status);
        }
        
        // Filter by payment method if provided
        if ($request->has('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        
        // Filter by date range if provided
        if ($request->has('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        
        if ($request->has('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        
        // Apply sorting
        $sortBy = $request->sort_by ?? 'created_at';
        $sortDirection = $request->sort_direction ?? 'desc';
        
        if ($sortBy === 'date') {
            $sortBy = 'created_at';
        } elseif ($sortBy === 'price') {
            $sortBy = 'net_price_for_driver';
        }
        
        $query->orderBy($sortBy, $sortDirection);
        
        // Pagination
        $perPage = $request->per_page ?? 15;
        $orders = $query->with(['user:id,name,phone,photo', 'service:id,name'])->paginate($perPage);
        
        // Transform data to include status text and other helper methods
        $orders->getCollection()->transform(function ($order) {
            $order->status_text = $order->getStatusText();
            $order->payment_method_text = $order->getPaymentMethodText();
            $order->payment_status_text = $order->getPaymentStatusText();
            $order->distance = $order->getDistance();
            return $order;
        });
        
        return response()->json([
            'success' => true,
            'message' => 'Orders retrieved successfully',
            'data' => $orders,
            'meta' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total()
            ]
        ]);
    }
    
    /**
     * Get active orders (accepted, in progress)
     *
     * @return \Illuminate\Http\Response
     */
    public function activeOrders()
    {
        $driver = Auth::guard('driver-api')->user();
        
        $orders = Order::where('driver_id', $driver->id)
            ->whereIn('status', [2, 3, 4]) // Only orders accepted by driver and in progress
            ->with(['user:id,name,phone,photo,fcm_token', 'service:id,name'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Transform data to include status text and other helper methods
        $orders->transform(function ($order) {
            $order->status_text = $order->getStatusText();
            $order->payment_method_text = $order->getPaymentMethodText();
            $order->distance = $order->getDistance();
            return $order;
        });
        
        return response()->json([
            'success' => true,
            'message' => 'Active orders retrieved successfully',
            'data' => $orders
        ]);
    }
    
    /**
     * Get completed orders history
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function completedOrders(Request $request)
    {
        $driver = Auth::guard('driver-api')->user();
        
        $validator = Validator::make($request->all(), [
            'per_page' => 'sometimes|integer|min:5|max:100',
            'from_date' => 'sometimes|date_format:Y-m-d',
            'to_date' => 'sometimes|date_format:Y-m-d|after_or_equal:from_date'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $query = Order::where('driver_id', $driver->id)
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
        $orders = $query->with(['user:id,name,phone,photo', 'service:id,name'])
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
        
        return response()->json([
            'success' => true,
            'message' => 'Completed orders retrieved successfully',
            'data' => $orders,
            'meta' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total()
            ]
        ]);
    }
    
    /**
     * Get cancelled orders
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function cancelledOrders(Request $request)
    {
        $driver = Auth::guard('driver-api')->user();
        
        $validator = Validator::make($request->all(), [
            'per_page' => 'sometimes|integer|min:5|max:100',
            'cancelled_by' => 'sometimes|in:user,driver,all'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $query = Order::where('driver_id', $driver->id);
        
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
        $orders = $query->with(['user:id,name,phone,photo', 'service:id,name'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
        
        // Transform data to include status text and other helper methods
        $orders->getCollection()->transform(function ($order) {
            $order->status_text = $order->getStatusText();
            $order->payment_method_text = $order->getPaymentMethodText();
            $order->distance = $order->getDistance();
            return $order;
        });
        
        return response()->json([
            'success' => true,
            'message' => 'Cancelled orders retrieved successfully',
            'data' => $orders,
            'meta' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total()
            ]
        ]);
    }
    
   
    
    /**
     * Display the specified order details
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $driver = Auth::guard('driver-api')->user();
        
        $order = Order::where('id', $id)
            ->where('driver_id', $driver->id)
            ->with([
                'user:id,name,phone,photo,fcm_token',
                'service:id,name,description,photo'
            ])
            ->first();
        
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }
        
        // Add helper attributes
        $order->status_text = $order->getStatusText();
        $order->payment_method_text = $order->getPaymentMethodText();
        $order->payment_status_text = $order->getPaymentStatusText();
        $order->distance = $order->getDistance();
        $order->discount_percentage = $order->getDiscountPercentage();
        
        return response()->json([
            'success' => true,
            'message' => 'Order details retrieved successfully',
            'data' => $order
        ]);
    }
    
    /**
     * Cancel an order by the driver
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function cancelOrder(Request $request, $id)
    {
        $driver = Auth::guard('driver-api')->user();
        
        $order = Order::where('id', $id)
            ->where('driver_id', $driver->id)
            ->first();
        
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }
        
        // Check if order can be cancelled
        if (!in_array($order->status, [2, 3])) {
            return response()->json([
                'success' => false,
                'message' => 'Order cannot be cancelled at this stage'
            ], 400);
        }
        
        $validator = Validator::make($request->all(), [
            'reason_for_cancel' => 'required|string|max:255'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        
        // Process cancellation
        $order->status = 7; // Driver cancelled order
        $order->reason_for_cancel = $request->reason_for_cancel;
        $order->save();
        
        // Notify the user about cancellation
        // This is a placeholder - implement your notification logic
        
        return response()->json([
            'success' => true,
            'message' => 'Order cancelled successfully',
            'data' => [
                'order_id' => $order->id,
                'status' => $order->status,
                'status_text' => $order->getStatusText(),
                'cancellation_reason' => $order->reason_for_cancel
            ]
        ]);
    }
    
    /**
     * Update order status (for driver to update progress)
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id)
    {
        $driver = Auth::guard('driver-api')->user();
        
        $order = Order::where('id', $id)
            ->where('driver_id', $driver->id)
            ->first();
        
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }
        
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:3,4,5'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $newStatus = $request->status;
        $currentStatus = $order->status;
        
        // Validate status transition
        $validTransitions = [
            2 => [3], // From accepted to going to user
            3 => [4], // From going to user to user with driver
            4 => [5]  // From user with driver to delivered
        ];
        
        if (!isset($validTransitions[$currentStatus]) || !in_array($newStatus, $validTransitions[$currentStatus])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid status transition'
            ], 400);
        }
        
        // Update status
        $order->status = $newStatus;
        
        // If status is delivered, also update payment status for cash payments
        if ($newStatus == 5 && $order->payment_method == 1) {
            $order->status_payment = 2; // Mark as paid for cash payments
        }
        
        $order->save();
        
        // Notify the user about status update
        // This is a placeholder - implement your notification logic
        
        // Add helper attributes
        $order->status_text = $order->getStatusText();
        $order->payment_status_text = $order->getPaymentStatusText();
        
        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully',
            'data' => [
                'order_id' => $order->id,
                'status' => $order->status,
                'status_text' => $order->getStatusText(),
                'payment_status' => $order->status_payment,
                'payment_status_text' => $order->getPaymentStatusText()
            ]
        ]);
    }
    
}