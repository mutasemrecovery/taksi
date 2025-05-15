<?php


namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
     public function index()
    {
        $data = Service::get();

        return response()->json(['data' => $data]);
    }


}
