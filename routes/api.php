<?php

use App\Http\Controllers\Auth\loginController;
use App\Http\Controllers\CitiesController;
use App\Http\Controllers\CountriesController;
use App\Http\Controllers\CurrenciesController;
use App\Http\Controllers\DisciplinesController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\EducationDegreeController;
use App\Http\Controllers\EducationLanguageController;
use App\Http\Controllers\ExamsController;
use App\Http\Controllers\GradingSchemeController;
use App\Http\Controllers\InstitutionalTypesController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\SpecialtiesController;
use App\Http\Controllers\StatesController;
use App\Http\Controllers\UniSpecialtiesController;
use App\Http\Controllers\UserController;
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


Route::prefix('v1')->group(function () {
    Route::post('/login', [loginController::class, 'login']);
    Route::group(['middleware'=> ['auth:sanctum']], function(){
        Route::resource('language', LanguageController::class);

        Route::resource('role', RolesController::class);

        Route::resource('users', UserController::class);

        Route::get('current-user', [UserController::class, 'getCurrentUser']);

        Route::resource('countries', CountriesController::class);

        Route::resource('state', StatesController::class);

        Route::resource('cities', CitiesController::class);

        Route::resource('institutionalType', InstitutionalTypesController::class);

        Route::resource('institution', EducationController::class);

        Route::resource('disciplines', DisciplinesController::class);

        Route::resource('educationDegree', EducationDegreeController::class);

        Route::resource('gradingScheme', GradingSchemeController::class);

        Route::resource('specialty', SpecialtiesController::class);

        Route::resource('exams', ExamsController::class);

        Route::resource('education-language', EducationLanguageController::class);

        Route::resource('currency', CurrenciesController::class);

        Route::resource('uniSpeciality', UniSpecialtiesController::class);
    });
});



