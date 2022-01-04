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
use App\Http\Controllers\InstitutionsController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ProgramsController;
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

    Route::resource('language', LanguageController::class);
    Route::post('/login', [loginController::class, 'login']);
    Route::group(['middleware'=> ['auth:sanctum']], function(){



        Route::resource('role', RolesController::class);

        Route::resource('users', UserController::class);


        Route::get('current-user', [UserController::class, 'getCurrentUser']);

        Route::resource('countries', CountriesController::class);

        Route::put('countries-active-deactive/{id}', [CountriesController::class, 'activeDeactive']);

        Route::resource('state', StatesController::class);

        Route::put('state-active-deactive/{id}', [StatesController::class, 'activeDeactive']);

        Route::resource('cities', CitiesController::class);

        Route::put('city-active-deactive/{id}', [CitiesController::class, 'activeDeactive']);

        Route::resource('institutionalType', InstitutionalTypesController::class);

        Route::put('institutionalType-active-deactive/{id}', [InstitutionalTypesController::class, 'activeDeactive']);

        Route::resource('institution', InstitutionsController::class);

        Route::put('institutions-active-deactive/{id}', [InstitutionsController::class, 'activeDeactive']);

        Route::resource('disciplines', DisciplinesController::class);

        Route::put('discipline-active-deactive/{id}', [DisciplinesController::class, 'activeDeactive']);

        Route::resource('educationDegree', EducationDegreeController::class);

        Route::put('educationDegree-active-deactive/{id}', [EducationDegreeController::class, 'activeDeactive']);

        Route::resource('gradingScheme', GradingSchemeController::class);


        Route::resource('exams', ExamsController::class);

        Route::resource('education-language', EducationLanguageController::class);

        Route::put('education-language-active-deactive/{id}', [EducationLanguageController ::class, 'activeDeactive']);


        Route::resource('currency', CurrenciesController::class);

        Route::resource('programs', ProgramsController::class);

        Route::put('program-active-deactive/{id}', [ProgramsController::class, 'activeDeactive']);
    });
});



