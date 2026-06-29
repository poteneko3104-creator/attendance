<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AttendanceRecordController;
use App\Models\User;
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
Route::get('/v1/attendance-records', [AttendanceRecordController::class, 'index']);
Route::post('/v1/login-token', function () {
    $user = User::find(1);
    $token = $user->createToken('test-token')->plainTextToken;
    return response()->json(['token' => $token]);
});
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/v1/attendance-records/{id}', [AttendanceRecordController::class, 'show']);
    Route::post('/v1/attendance-records', [AttendanceRecordController::class, 'store']);
    Route::put('/v1/attendance-records/{id}', [AttendanceRecordController::class, 'update']);
    Route::delete('/v1/attendance-records/{id}', [AttendanceRecordController::class, 'destroy']);
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
