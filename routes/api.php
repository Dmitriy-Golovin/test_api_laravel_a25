<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\WorkReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::group(['prefix'=>'post'], function () {
//     Route::post('/add-employee', [EmployeeController::class, 'add']);
//     Route::post('/add-report', [WorkReportController::class, 'add']);
//     Route::get('/salary-list', [WorkReportController::class, 'getSalaryList']);
//     Route::put('/pay-salary', [WorkReportController::class, 'paySalary']);
// });

Route::post('/add-employee', [EmployeeController::class, 'add']);
Route::post('/add-report', [WorkReportController::class, 'add']);
Route::get('/salary-list', [WorkReportController::class, 'getSalaryList']);
Route::put('/pay-salary', [WorkReportController::class, 'paySalary']);
