<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Option;

class OptionController extends Controller
{

    public function getOptions()
    {
        $data = Option::get();
        return response()->json(['data' => $data]);
    }

}
