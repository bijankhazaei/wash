<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

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

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout']);

Route::get('/main-report', [AdminController::class, 'getMainExcelFile']);

Route::get('/detailed-report-hospital/{hospital_type}', [AdminController::class, 'detailedReportHospital']);

Route::get('/detailed-report-government/{government_type}', [AdminController::class, 'detailedReportGovernment']);

Route::get('/detailed-report-urban/{urban_type}', [AdminController::class, 'detailedReportUrban']);

Route::get('/main-report-hospital/{hospital_type}', [AdminController::class, 'mainReportHospital']);

Route::get('/main-report-government/{government_type}', [AdminController::class, 'mainReportGovernment']);

Route::get('/main-report-area', [AdminController::class, 'mainReportArea']);

Route::get('/main-report-urban/{urban_type}', [AdminController::class, 'mainReportUrban']);
