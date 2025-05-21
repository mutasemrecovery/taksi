<?php


namespace App\Http\Controllers\Api\v1\Driver;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Order;
use App\Traits\Responses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HomeDriverController extends Controller
{
    use Responses;

   public function __invoke(Request $request)
    {
        // Get the authenticated driver
        $driver = auth('driver-api')->user();
        
        // Get driver's rating
        $rating = $driver->ratings()->avg('rating') ?? 0;
        
        // Prepare the response data
        $responseData = [
            'profile' => [
                $driver
            ],
            'rating' => round($rating, 1), // Round to 1 decimal place
        ];
        
        return $this->success_response('Home data retrieved successfully', $responseData);
    }
}
