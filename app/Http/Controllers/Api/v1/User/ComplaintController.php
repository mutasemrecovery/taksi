<?php


namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'status' => 'sometimes|in:1,2,3',
            'per_page' => 'sometimes|integer|min:5|max:100',
            'sort_by' => 'sometimes|in:date,status',
            'sort_direction' => 'sometimes|in:asc,desc'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $query = Complaint::where('user_id', $user->id);

        // Filter by status if provided
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Apply sorting
        $sortBy = $request->sort_by ?? 'created_at';
        $sortDirection = $request->sort_direction ?? 'desc';

        if ($sortBy === 'date') {
            $sortBy = 'created_at';
        }

        $query->orderBy($sortBy, $sortDirection);

        // Pagination
        $perPage = $request->per_page ?? 15;
        $complaints = $query->paginate($perPage);

        // Transform data to include status name
        $complaints->getCollection()->transform(function ($complaint) {
            $complaint->status_name = $complaint->getStatusNameAttribute();
            return $complaint;
        });

        return response()->json([
            'success' => true,
            'message' => 'Complaints retrieved successfully',
            'data' => $complaints,
            'meta' => [
                'current_page' => $complaints->currentPage(),
                'last_page' => $complaints->lastPage(),
                'per_page' => $complaints->perPage(),
                'total' => $complaints->total()
            ]
        ]);
    }

    /**
     * Store a newly created complaint
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'order_id' => 'sometimes|exists:orders,id',
            'driver_id' => 'sometimes|exists:drivers,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // If order_id is provided, verify it belongs to the user
        if ($request->has('order_id')) {
            $order = Order::find($request->order_id);

            if (!$order || $order->user_id != $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found or does not belong to you'
                ], 404);
            }

            // If driver_id is not provided, get it from the order
            if (!$request->has('driver_id') && $order->driver_id) {
                $request->merge(['driver_id' => $order->driver_id]);
            }
        }

        $complaint = new Complaint();
        $complaint->subject = $request->subject;
        $complaint->description = $request->description;
        $complaint->user_id = $user->id;
        $complaint->order_id = $request->order_id;
        $complaint->driver_id = $request->driver_id;
        $complaint->status = Complaint::STATUS_PENDING;
        $complaint->save();

        // Add status name to response
        $complaint->status_name = $complaint->getStatusNameAttribute();

        return response()->json([
            'success' => true,
            'message' => 'Complaint submitted successfully',
            'data' => $complaint
        ], 201);
    }

    /**
     * Display the specified complaint
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();

        $complaint = Complaint::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$complaint) {
            return response()->json([
                'success' => false,
                'message' => 'Complaint not found'
            ], 404);
        }

        // Add status name and load relationships
        $complaint->status_name = $complaint->getStatusNameAttribute();
        $complaint->load(['driver:id,name,phone,photo', 'order:id,order_number,created_at,total']);

        return response()->json([
            'success' => true,
            'message' => 'Complaint details retrieved successfully',
            'data' => $complaint
        ]);
    }

    /**
     * Update the specified complaint
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        $complaint = Complaint::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$complaint) {
            return response()->json([
                'success' => false,
                'message' => 'Complaint not found'
            ], 404);
        }

        // Only allow updates if the complaint is still pending
        if ($complaint->status != Complaint::STATUS_PENDING) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot update complaint that is already being processed'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'subject' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->has('subject')) {
            $complaint->subject = $request->subject;
        }

        if ($request->has('description')) {
            $complaint->description = $request->description;
        }

        $complaint->save();

        // Add status name to response
        $complaint->status_name = $complaint->getStatusNameAttribute();

        return response()->json([
            'success' => true,
            'message' => 'Complaint updated successfully',
            'data' => $complaint
        ]);
    }

    /**
     * Remove the specified complaint
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();

        $complaint = Complaint::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$complaint) {
            return response()->json([
                'success' => false,
                'message' => 'Complaint not found'
            ], 404);
        }

        // Only allow deletion if the complaint is still pending
        if ($complaint->status != Complaint::STATUS_PENDING) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete complaint that is already being processed'
            ], 400);
        }

        $complaint->delete();

        return response()->json([
            'success' => true,
            'message' => 'Complaint deleted successfully'
        ]);
    }
}
