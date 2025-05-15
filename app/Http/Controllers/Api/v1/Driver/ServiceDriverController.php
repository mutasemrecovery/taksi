<?php


namespace App\Http\Controllers\Api\v1\Driver;

use App\Http\Controllers\Controller;
use App\Models\DriverService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceDriverController extends Controller
{
    public function storeOrUpdateStatus(Request $request)
    {
        $driver_id = auth()->guard('driver-api')->user()->id;
        // Validate request
        $validator = Validator::make($request->all(), [
            'service_id' => 'required|exists:services,id',
            'status' => 'required|in:1,2', // 1 active, 2 inactive
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if record exists
        $driverService = DriverService::where('driver_id', $driver_id)
            ->where('service_id', $request->service_id)
            ->first();

        if ($driverService) {
            // Update existing record
            $driverService->status = $request->status;
            $driverService->save();
            
            $message = 'Driver service status updated successfully';
        } else {
            // Create new record
            $driverService = new DriverService();
            $driverService->driver_id = $driver_id;
            $driverService->service_id = $request->service_id;
            $driverService->status = $request->status;
            $driverService->save();
            
            $message = 'Driver service created successfully';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $driverService
        ], 200);
    }

}
