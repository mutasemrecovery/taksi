<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use App\Models\ClassTeacher;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Admin\FCMController; // <-- Import the FCMController here
use App\Models\ParentStudent;
use Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function active()
    {
        $user = auth()->user();
        if ($user->activate == 2) {
            return response(['errors' => ['Your account has been InActive']], 403);
        }

        return response()->json(['user' => $user]);
    }

    public function deleteAccount(Request $request)
    {
        $user = auth()->user(); // Get the authenticated user

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        try {
            // Update the `activate` column to 2
            $user->update(['activate' => 2]);

            return response()->json(['message' => 'Account Deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to deactivate account', 'error' => $e->getMessage()], 500);
        }
    }

    
  public function checkPhone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string',
            'fcm_token' => 'nullable|string',
            'user_type' => 'nullable|in:user,driver' // Optional: To determine if checking user or driver
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $phone = $request->phone;
        $userType = $request->user_type ?? 'user'; // Default to user if not specified
        
        // Determine which model to check based on user_type
        $model = ($userType == 'driver') ? 'App\Models\Driver' : 'App\Models\User';
        $user = $model::where('phone', $phone)->first();


        if ($user) {
            // Check if user is active
            if ($user->activate == 2) {
                return response()->json([
                    'status' => false,
                    'message' => 'Account is inactive',
                    'user_exists' => true,
                    'account_status' => 'inactive'
                ], 403);
            }

            // Update FCM token if provided
            if ($request->has('fcm_token')) {
                $user->fcm_token = $request->fcm_token;
                $user->save();
            }

                // Create access token
        $accessToken = $user->createToken('authToken')->accessToken;
            return response()->json([
                'status' => true,
                'message' => 'Success',
                'user_exists' => true,
                'account_status' => 'active',
                'user_type' => $userType,
                'user' => $user,
                'token' => $accessToken,
            ], 200);
        }

        // User doesn't exist, 
        return response()->json([
            'status' => true, 
            'message' => 'Phone number not registered. OTP sent for registration',
            'user_exists' => false,
            'user_type' => $userType,
        ], 200);
    }



     public function register(Request $request)
    {
        $userType = $request->user_type ?? 'user';
        
        // Different validation rules based on user type
        if ($userType == 'driver') {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'country_code' => 'required',
                'phone' => 'required|string|unique:drivers',
                'email' => 'nullable|email|unique:drivers',
                'fcm_token' => 'nullable|string',
                'option_id' => 'required|exists:options,id',
                // Add any other required driver fields
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'country_code' => 'required',
                'phone' => 'required|string|unique:users',
                'email' => 'nullable|email|unique:users',
                'fcm_token' => 'nullable|string',
                // Add any other required user fields
            ]);
        }

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Create user with the data
        if ($userType == 'driver') {
            $user = \App\Models\Driver::create([
                'name' => $request->name,
                'country_code' => $request->country_code,
                'phone' => $request->phone,
                'email' => $request->email,
                'fcm_token' => $request->fcm_token,
                'option_id' => $request->option_id,
                'activate' => 1, // Default to active
                'balance' => 0,  // Default balance
                // Set other driver fields
            ]);
        } else {
            $user = User::create([
                'name' => $request->name,
                'country_code' => $request->country_code,
                'phone' => $request->phone,
                'email' => $request->email,
                'fcm_token' => $request->fcm_token,
                'activate' => 1, // Default to active
                'balance' => 0,  // Default balance
                'referral_code' => $this->generateReferralCode(),
            ]);
        }

        // Generate access token
        $accessToken = $user->createToken('authToken')->accessToken;

        return response()->json([
            'status' => true,
            'message' => 'Registration successful',
            'token' => $accessToken,
            'user' => $user,
            'new_user' => true
        ], 200);
    }

    public function userProfile()
    {
        // Authenticate the user
        $user = auth()->user();

        // Return the user's profile
        return response([
            'user' => $user,
        ], 200);
    }




    public function updateProfile(Request $request)
    {
        // Authenticate the user
        $user = auth()->user();

        // Validate input data
        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id, // Ensure email is unique
            'phone' => 'nullable|string',
            'photo' => 'nullable|image|max:2048', // Optional photo upload
        ]);

        // Handle file upload for photo (if provided)

        if ($request->has('photo')) {
            $data['photo'] = uploadImage('assets/admin/uploads', $request->photo);
        }

        // Update user data
        $user->update($data);

        // Return updated user data
        return response([
            'message' => 'Profile updated successfully',
            'user' => $user,
        ], 200);
    }



     public function notifications()
    {
        $user = auth()->user();

        // Define user_type-based notification types
        $userTypeMapping = [
            1 => 1, // Regular Users
            2 => 3, // Teachers
            3 => 2, // Parents
        ];

        // Fetch notifications
        $notifications = Notification::query()
            ->where(function ($query) use ($user, $userTypeMapping) {
                $query->where('type', 0) // Global notifications (for all users)
                      ->orWhere(function ($q) use ($user) {
                          // Notifications specifically for this user
                          $q->where('type', 4)->where('user_id', $user->id);
                      });

                // Include user_type-specific notifications if applicable
                if (isset($userTypeMapping[$user->user_type])) {
                    $query->orWhere('type', $userTypeMapping[$user->user_type]);
                }
            })
            ->orderBy('id', 'DESC')
            ->get();

        return response(['data' => $notifications], 200);
    }




    public function sendToUser(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required',
            'title' => 'required|string',
            'body' => 'required|string'
        ]);

        try {
            // Call the sendMessageToUser method in the FCMController
            $response = FCMController::sendMessageToUser(
                $request->title,
                $request->body,
                $request->user_id,
            );

            if ($response) {

                return redirect()->back()->with('message', 'Notification sent successfully to the user');
            } else {
                return redirect()->back()->with('error', 'Notification was not sent to the user');
            }
        } catch (\Exception $e) {
            \Log::error('FCM Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    private function generateReferralCode()
    {
        do {
            $referralCode = strtoupper(substr(md5(time() . rand(1000, 9999)), 0, 8));
        } while (User::where('referral_code', $referralCode)->exists());
        
        return $referralCode;
    }
}
