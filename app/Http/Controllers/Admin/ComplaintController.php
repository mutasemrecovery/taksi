<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\User;
use App\Models\Driver;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the complaints.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $complaints = Complaint::with(['user', 'driver', 'order'])->latest()->paginate(10);
        return view('admin.complaints.index', compact('complaints'));
    }

    /**
     * Show the form for creating a new complaint.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
        $drivers = Driver::all();
        $orders = Order::all();
        return view('admin.complaints.create', compact('users', 'drivers', 'orders'));
    }

    /**
     * Store a newly created complaint in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
            'driver_id' => 'nullable|exists:drivers,id',
            'order_id' => 'nullable|exists:orders,id',
            'status' => 'required|in:1,2,3',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Complaint::create($request->all());

        return redirect()->route('admin.complaints.index')
            ->with('success', __('messages.Complaint_Created_Successfully'));
    }

    /**
     * Display the specified complaint.
     *
     * @param  \App\Models\Complaint  $complaint
     * @return \Illuminate\Http\Response
     */
    public function show(Complaint $complaint)
    {
        $complaint->load(['user', 'driver', 'order']);
        return view('admin.complaints.show', compact('complaint'));
    }

    /**
     * Show the form for editing the specified complaint.
     *
     * @param  \App\Models\Complaint  $complaint
     * @return \Illuminate\Http\Response
     */
    public function edit(Complaint $complaint)
    {
        $users = User::all();
        $drivers = Driver::all();
        $orders = Order::all();
        return view('admin.complaints.edit', compact('complaint', 'users', 'drivers', 'orders'));
    }

    /**
     * Update the specified complaint in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Complaint  $complaint
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Complaint $complaint)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
            'driver_id' => 'nullable|exists:drivers,id',
            'order_id' => 'nullable|exists:orders,id',
            'status' => 'required|in:1,2,3',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $complaint->update($request->all());

        return redirect()->route('admin.complaints.index')
            ->with('success', __('messages.Complaint_Updated_Successfully'));
    }

    /**
     * Update complaint status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Complaint  $complaint
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, Complaint $complaint)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:1,2,3',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $complaint->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => __('messages.Status_Updated_Successfully'),
            'status_label' => $complaint->status_label,
            'status_badge' => $complaint->status_badge
        ]);
    }

    /**
     * Remove the specified complaint from storage.
     *
     * @param  \App\Models\Complaint  $complaint
     * @return \Illuminate\Http\Response
     */
    public function destroy(Complaint $complaint)
    {
        $complaint->delete();

        return redirect()->route('admin.complaints.index')
            ->with('success', __('messages.Complaint_Deleted_Successfully'));
    }
}