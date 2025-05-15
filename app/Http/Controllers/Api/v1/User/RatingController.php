<?php


namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use App\Models\Setting;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $rating = new Rating();
        
        // Associate the upload with the authenticated user
        $rating->user_id = auth()->user()->id;
        $rating->rating = $request->rating;
        $rating->review = $request->review;
        $rating->driver_id = $request->driver_id;
    
        if ($rating->save()) {
            return response()->json(['message' => 'Rating Submit Successfully', 'data' => $rating]);
        } else {
            return response()->json(['error' => 'Something went wrong']);
        }
    }


}
