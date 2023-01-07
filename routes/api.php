<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HealthCareCenterController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\QuestionnaireController;
use App\Http\Controllers\UniversityController;
use App\Http\Controllers\UserController;
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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [AuthController::class, 'getUser']);

    Route::get('provinces/{id}', [ProvinceController::class, 'getProvinceCites']);

    Route::get('provinces', [ProvinceController::class, 'getProvinces']);

    Route::get('users', [UserController::class, 'users']);

    //Route::post('update-user/{id}', [UserController::class, 'editUser']);

   // Route::post('add-user', [UserController::class, 'addUser']);

    Route::get('questions/{section}', [QuestionnaireController::class, 'getQuestions']);

    Route::get('answers/{center_id}/{section}', [QuestionnaireController::class, 'getAnswers']);

    Route::get('hcfs', [HealthCareCenterController::class, 'getList']);

    Route::get('hcfs-by-university/{id}', [HealthCareCenterController::class, 'getListByUniversity']);

    //Route::post('add-hcf', [HealthCareCenterController::class, 'addNewHealthCareCenter']);

    //Route::post('edit-hcf/{id}', [HealthCareCenterController::class, 'editHealthCareCenter']);

    Route::get('universities', [UniversityController::class, 'getList']);

    Route::get('enumerator-centers/{city}', [HealthCareCenterController::class, 'getEnumeratorCenters']);

    Route::get('get-health-care-center/{id}', [HealthCareCenterController::class, 'getHealthCareCenter']);

    //Route::post('save-questionnaire/{id}', [HealthCareCenterController::class, 'saveQuestionnaire']);

    //Route::post('save-answers/{id}', [HealthCareCenterController::class, 'saveQuestionnaireAnswers']);

    Route::get('get-university-data', [DashboardController::class, 'getUniversityData']);

    Route::get('get-area-data', [DashboardController::class, 'getAreaData']);

    Route::get('get-map-centers/{university_id}/{type}', [DashboardController::class, 'getMapCenters']);

    Route::get('get-completed-questionnaires/{university_id}',
        [QuestionnaireController::class, 'getCompletedQuestionnaires']);

    Route::get('questionnaire/{id}', [QuestionnaireController::class, 'getQuestionnaire']);
});

Route::group(['prefix' => 'administration', 'as' => 'administration'], function () {

    Route::post('import-health-care-center', [AdminController::class, 'importHealthCareCenters']);
});

Route::get('main-report' , [AdminController::class, 'getMainExcelFileApi']);
