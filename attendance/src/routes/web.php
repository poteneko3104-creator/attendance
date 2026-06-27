<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test', function () {
    return view('admin.list');
});
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/attendance', [AttendanceController::class, 'attendance']);
    Route::post('/attendance', [AttendanceController::class, 'submit']);
    Route::get('/list', [AttendanceController::class, 'list'])->name('attendance-list');
    Route::get('/detail', [AttendanceController::class, 'detail'])->name('attendance-detail');
    Route::post('/detail', [AttendanceController::class, 'sendApplication']);
    Route::get('/applicationList', [AttendanceController::class, 'applicationList'])->name('application_list');
});
Route::prefix('admin')->name('admin.')->middleware(['guest:admin'])->group(function () {
    Route::get('/login', [AdminController::class, 'login'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::prefix('admin')->name('admin.')->middleware(['auth:admin'])->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/attendance/list', [AdminController::class, 'attendanceList'])->name('admin_attendance-list');
    Route::get('/admin/attendance/detail', [AdminController::class, 'detail'])->name('admin_attendance-detail');
    Route::post('/admin/attendance/detail', [AdminController::class, 'update']);
    Route::get('/admin/attendance/staffList', [AdminController::class, 'staffList']);
    Route::get('/admin/attendance/staff', [AdminController::class, 'staffAttendance'])->name('admin_staff-attendance');
});