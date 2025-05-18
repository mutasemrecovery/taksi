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
use App\Traits\Responses;
use Auth;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    use Responses;

    public function active()
    {
        $user = auth()->user();
        if ($user->activate == 2) {
            return $this->error_response('Your account has been InActive', null);
        }

        return $this->success_response('User retrieved successfully', $user);
    }

    public function deleteAccount(Request $request)
    {
        try {
            // Check both authentication guards
            $userApi = auth('user-api')->user();
            $driverApi = auth('driver-api')->user();
            
            if ($userApi) {
                // Regular user account deactivation
                $userApi->update(['activate' => 2]);
                
                // Revoke all tokens for the user
                $userApi->tokens()->delete();
                
                return $this->success_response('User account deleted successfully', null);
            } elseif ($driverApi) {
                // Driver account deactivation
                $driverApi->update(['activate' => 2]);
                
                // Revoke all tokens for the driver
                $driverApi->tokens()->delete();
                
                return $this->success_response('Driver account deleted successfully', null);
            } else {
                return $this->error_response('Unauthenticated', [], 401);
            }
        } catch (\Exception $e) {
            \Log::error('Account deletion error: ' . $e->getMessage());
            return $this->error_response('Failed to delete account', ['error' => $e->getMessage()]);
        }
    }
       public function logout()
    {
        try {
            // Check if the request is authenticated with user-api guard
            $userApi = auth('user-api')->user();
            
            // Check if the request is authenticated with driver-api guard
            $driverApi = auth('driver-api')->user();
            
            if ($userApi) {
                // Revoke all tokens for user
                $userApi->tokens()->delete();
                return $this->success_response('User logout successful', []);
            } elseif ($driverApi) {
                // Revoke all tokens for driver
                $driverApi->tokens()->delete();
                return $this->success_response('Driver logout successful', []);
            } else {
                return $this->error_response('Unauthenticated', [], 401);
            }
        } catch (\Throwable $th) {
            // Log the error for debugging
            \Log::error('Logout error: ' . $th->getMessage());
            return $this->error_response('Failed to logout', []);
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
            return $this->error_response('Validation error', $validator->errors());
        }

        $phone = $request->phone;
        $userType = $request->user_type ?? 'user'; // Default to user if not specified
        
        // Determine which model to check based on user_type
        $model = ($userType == 'driver') ? 'App\Models\Driver' : 'App\Models\User';
        $user = $model::where('phone', $phone)->first();

        if ($user) {
            // Check if user is active
            if ($user->activate == 2) {
                return $this->error_response('Account is inactive', [
                    'user_exists' => true,
                    'account_status' => 'inactive'
                ]);
            }

            // Update FCM token if provided
            if ($request->has('fcm_token')) {
                $user->fcm_token = $request->fcm_token;
                $user->save();
            }

            // Create access token
            $accessToken = $user->createToken('authToken')->accessToken;
            
            return $this->success_response('Success', [
                'user_exists' => true,
                'account_status' => 'active',
                'user_type' => $userType,
                'user' => $user,
                'token' => $accessToken,
            ]);
        }

        // User doesn't exist
        return $this->success_response('Phone number not registered. OTP sent for registration', [
            'user_exists' => false,
            'user_type' => $userType,
        ]);
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
            return $this->error_response('Validation error', $validator->errors());
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

        return $this->success_response('Registration successful', [
            'token' => $accessToken,
            'user' => $user,
            'new_user' => true
        ]);
    }

    public function userProfile()
    {
        try {
            // Check both authentication guards
            $userApi = auth('user-api')->user();
            $driverApi = auth('driver-api')->user();
            
            if ($userApi) {
                // If it's a regular user
                return $this->success_response('User profile retrieved', $userApi);
            } elseif ($driverApi) {
                // If it's a driver, make sure photo URL is properly formatted
                // This assumes you've added the photo_url accessor to your Driver model
                return $this->success_response('Driver profile retrieved', $driverApi);
            } else {
                return $this->error_response('Unauthenticated', [], 401);
            }
        } catch (\Throwable $th) {
            \Log::error('Profile retrieval error: ' . $th->getMessage());
            return $this->error_response('Failed to retrieve profile', []);
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            // Check both authentication guards
            $userApi = auth('user-api')->user();
            $driverApi = auth('driver-api')->user();
            
            // Determine which type of user is authenticated
            if ($userApi) {
                $user = $userApi;
                $userType = 'user';
                $table = 'users';
            } elseif ($driverApi) {
                $user = $driverApi;
                $userType = 'driver';
                $table = 'drivers';
            } else {
                return $this->error_response('Unauthenticated', [], 401);
            }
            
            // Customize validation rules based on user type
            $validationRules = [
                'name' => 'nullable|string|max:255',
                'email' => 'nullable|email|unique:' . $table . ',email,' . $user->id,
                'phone' => 'nullable|string',
                'sos_phone' => 'nullable|string',
                'photo' => 'nullable|image|max:2048',
            ];
            
            // Add driver-specific validation rules if needed
            if ($userType == 'driver') {
                // For example:
                // $validationRules['license_number'] = 'nullable|string';
            }
            
            // Validate input data
            $validator = Validator::make($request->all(), $validationRules);
            
            if ($validator->fails()) {
                return $this->error_response('Validation error', $validator->errors());
            }
            
            // Get fillable fields based on user type
            $data = $request->only(['name', 'email', 'phone', 'sos_phone']);
            
            // Add driver-specific fields if needed
            if ($userType == 'driver') {
                // For example:
                // $driverFields = $request->only(['license_number', 'car_model']);
                // $data = array_merge($data, $driverFields);
            }
            
            // Handle file upload for photo (if provided)
            if ($request->hasFile('photo')) {
                // Use the same upload path for all user types
                $data['photo'] = uploadImage('assets/admin/uploads', $request->file('photo'));
            }
            
            // Update user data
            $user->update($data);
            
            return $this->success_response(ucfirst($userType) . ' profile updated successfully', $user);
        } catch (\Throwable $th) {
            \Log::error('Profile update error: ' . $th->getMessage());
            return $this->error_response('Failed to update profile', []);
        }
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

        return $this->success_response('Notifications retrieved successfully', $notifications);
    }

    public function sendToUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'title' => 'required|string',
            'body' => 'required|string'
        ]);

        if ($validator->fails()) {
            return $this->error_response('Validation error', $validator->errors());
        }

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
