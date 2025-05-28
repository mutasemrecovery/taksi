<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Service;
use App\Traits\Responses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class NotificationApiController extends Controller
{
    use Responses;


        // Fetch notifications for a user
    public function getUserNotifications(Request $request)
    {
        $user = $request->user(); // assuming sanctum or passport is used for auth
        $notifications = Notification::where(function ($query) use ($user) {
            $query->where('type', 0) // all
                ->orWhere('type', 1) // user
                ->orWhere(function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
        })->latest()->get();
        return response()->json([
            'status' => true,
            'notifications' => $notifications,
        ]);
    }
    // Fetch notifications for a driver
    public function getDriverNotifications(Request $request)
    {
        $driver = $request->user(); // assuming driver is authenticated
        $notifications = Notification::where(function ($query) use ($driver) {
            $query->where('type', 0) // all
                ->orWhere('type', 2) // driver
                ->orWhere(function ($q) use ($driver) {
                    $q->where('driver_id', $driver->id);
                });
        })->latest()->get();
        return response()->json([
            'status' => true,
            'notifications' => $notifications,
        ]);
    }
}


