<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Service;
use App\Models\ServicePayment;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = Service::all();
        return view('admin.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.services.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
      public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'start_price' => 'required|numeric|min:0',
            'price_per_km' => 'required|numeric|min:0',
            'admin_commision' => 'required|numeric|min:0',
            'activate' => 'required',
            'type_of_commision' => 'required|in:1,2',
            'is_electric' => 'required|in:1,2',
            'payment_methods' => 'required|array', // Changed to array
            'payment_methods.*' => 'required|in:1,2,3', // Validate each payment method
            'capacity' => 'required|integer|min:0',
            'waiting_time' => 'required|numeric|min:0',
            'cancellation_fee' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('services.create')
                ->withErrors($validator)
                ->withInput();
        }

        $serviceData = $request->except(['photo', 'payment_methods']);

        // Handle photo upload
        if ($request->has('photo')) {
            $serviceData['photo'] = uploadImage('assets/admin/uploads', $request->photo);
        }

        // Create service
        $service = Service::create($serviceData);

        // Create payment methods
        foreach ($request->payment_methods as $paymentMethod) {
            ServicePayment::create([
                'service_id' => $service->id,
                'payment_method' => $paymentMethod
            ]);
        }

        return redirect()
            ->route('services.index')
            ->with('success', __('messages.Service_Created_Successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $service = Service::findOrFail($id);
        return view('admin.services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $service = Service::findOrFail($id);
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'start_price' => 'required|numeric|min:0',
            'activate' => 'required',
            'price_per_km' => 'required|numeric|min:0',
            'admin_commision' => 'required|numeric|min:0',
            'type_of_commision' => 'required|in:1,2',
            'is_electric' => 'required|in:1,2',
            'payment_methods' => 'required|array', // Changed to array
            'payment_methods.*' => 'required|in:1,2,3', // Validate each payment method
            'capacity' => 'required|integer|min:0',
            'waiting_time' => 'required|numeric|min:0',
            'cancellation_fee' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('services.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }

        $serviceData = $request->except(['photo', 'payment_methods']);

        // Handle photo upload
        if ($request->has('photo')) {
            // Delete old photo if exists
            if ($service->photo && file_exists('assets/admin/uploads/' . $service->photo)) {
                unlink('assets/admin/uploads/' . $service->photo);
            }
            
            $serviceData['photo'] = uploadImage('assets/admin/uploads', $request->photo);
        }

        // Update service
        $service->update($serviceData);

        // Delete existing payment methods
        $service->servicePayments()->delete();

        // Create new payment methods
        foreach ($request->payment_methods as $paymentMethod) {
            ServicePayment::create([
                'service_id' => $service->id,
                'payment_method' => $paymentMethod
            ]);
        }

        return redirect()
            ->route('services.index')
            ->with('success', __('messages.Service_Updated_Successfully'));
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        
        // Delete service photo if exists
        if ($service->photo && file_exists('assets/admin/uploads/' . $service->photo)) {
            unlink('assets/admin/uploads/' . $service->photo);
        }
        
        $service->delete();

        return redirect()
            ->route('services.index')
            ->with('success', __('messages.Service_Deleted_Successfully'));
    }
}
