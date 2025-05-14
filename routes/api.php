<?php

namespace App\Http\Controllers\Api\v1\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\User\AuthController;
use App\Http\Controllers\Api\v1\User\ParentStudentController;
use App\Http\Controllers\Api\v1\User\AttendanceController;
use App\Http\Controllers\Api\v1\User\ExamController;
use App\Http\Controllers\Api\v1\User\GradeController;
use App\Http\Controllers\Api\v1\User\ClasController;
use App\Http\Controllers\Api\v1\User\NoteStudentController;
use App\Http\Controllers\Api\v1\User\StudentController;
use App\Http\Controllers\Api\v1\User\WorkPaperController;
use App\Http\Controllers\Api\v1\User\UploadPhotoVoiceController;
use App\Http\Controllers\Api\v1\User\TeacherController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Route unAuth
Route::group(['prefix' => 'v1/user'], function () {

    //---------------- Auth --------------------//
  Route::get('/getOptions', [OptionController::class, 'getOptions']);
  Route::post('/check-phone', [AuthController::class, 'checkPhone']);
  Route::post('/register', [AuthController::class, 'register']);

    Route::get('/settings', [SettingController::class, 'index']);

    // Auth Route
    Route::group(['middleware' => ['auth:user-api']], function () {

         Route::get('/active', [AuthController::class, 'active']);

        // image for chat
        Route::get('/uploadPhotoVoice', [UploadPhotoVoiceController::class,'index']);
        Route::post('/uploadPhotoVoice', [UploadPhotoVoiceController::class,'store']);

        Route::post('/update_profile', [AuthController::class, 'updateProfile']);
        Route::post('/delete_account', [AuthController::class, 'deleteAccount']);
        Route::get('/userProfile', [AuthController::class, 'userProfile']);
        Route::get('/pages/{type}', [PageController::class,'index']);

        //Notification
        Route::get('/notifications', [AuthController::class, 'notifications']);
        Route::post('/notifications', [AuthController::class, 'sendToUser']);


      

    });


});
