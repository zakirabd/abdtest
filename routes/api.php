<?php

use App\Http\Controllers\Auth\loginController;
use App\Http\Controllers\CitiesController;
use App\Http\Controllers\CityFaqsController;
use App\Http\Controllers\CountriesController;
use App\Http\Controllers\CountryFagController;
use App\Http\Controllers\CountryWiseEducationController;
use App\Http\Controllers\CurrenciesController;
use App\Http\Controllers\DisciplineFaqsController;
use App\Http\Controllers\DisciplinesController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\EducationDegreeController;
use App\Http\Controllers\EducationLanguageController;
use App\Http\Controllers\ExamsController;
use App\Http\Controllers\GradingSchemeController;
use App\Http\Controllers\InstitutionalTypesController;
use App\Http\Controllers\InstitutionFaqsController;
use App\Http\Controllers\InstitutionsController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ProgramsController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\SpecialtiesController;
use App\Http\Controllers\StateFaqsController;
use App\Http\Controllers\StatesController;
use App\Http\Controllers\UniSpecialtiesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CountryEducationDegreeController;
use App\Http\Controllers\MatchingProgramController;
use App\Http\Controllers\StudentAppliedProgramController;
use App\Models\CountyFagsTranslate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentRoleMainController;

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
    Route::resource('role', RolesController::class);
    Route::group(['middleware'=> ['auth:sanctum']], function(){

        Route::resource('language', LanguageController::class);
        Route::post('/logOut', [loginController::class, 'logOut']);
        Route::resource('users', UserController::class);
        // Route::resource('role', RolesController::class);


        Route::put('users-active-deactive/{id}', [UserController::class, 'activeDeactive']);






        Route::get('current-user', [UserController::class, 'getCurrentUser']);

        Route::resource('countries', CountriesController::class);
        Route::get('country-data', [CountriesController::class, 'getCountryData']);
        Route::put('countries-active-deactive/{id}', [CountriesController::class, 'activeDeactive']);
        Route::resource('country-faqs', CountryFagController::class);

        Route::resource('currency', CurrenciesController::class);

        Route::resource('state', StatesController::class);
        Route::resource('state-faqs', StateFaqsController::class);
        Route::put('state-active-deactive/{id}', [StatesController::class, 'activeDeactive']);

        Route::resource('cities', CitiesController::class);
        Route::resource('city-faqs', CityFaqsController::class);
        Route::put('city-active-deactive/{id}', [CitiesController::class, 'activeDeactive']);


        Route::resource('institutionalType', InstitutionalTypesController::class);
        Route::put('institutionalType-active-deactive/{id}', [InstitutionalTypesController::class, 'activeDeactive']);


        Route::resource('institution', InstitutionsController::class);
        Route::resource('institution-faqs', InstitutionFaqsController::class);
        Route::put('institutions-active-deactive/{id}', [InstitutionsController::class, 'activeDeactive']);

        Route::resource('educationDegree', EducationDegreeController::class);
        Route::resource('country-education-degree', CountryEducationDegreeController::class);



        Route::resource('disciplines', DisciplinesController::class);
        Route::resource('discipline-faqs', DisciplineFaqsController::class);

        Route::put('discipline-active-deactive/{id}', [DisciplinesController::class, 'activeDeactive']);


        Route::put('educationDegree-active-deactive/{id}', [EducationDegreeController::class, 'activeDeactive']);

        Route::resource('gradingScheme', GradingSchemeController::class);


        Route::resource('exams', ExamsController::class);

        Route::resource('education-language', EducationLanguageController::class);

        Route::put('education-language-active-deactive/{id}', [EducationLanguageController ::class, 'activeDeactive']);


        Route::resource('programs', ProgramsController::class);
        Route::get('program-data', [ ProgramsController::class, 'getProgramData']);
        Route::put('program-active-deactive/{id}', [ProgramsController::class, 'activeDeactive']);

        Route::resource('country-wise-education', CountryWiseEducationController::class);


        Route::resource('student-main-data', StudentRoleMainController::class);
        Route::resource('student-apply', StudentAppliedProgramController::class);
        Route::post('student-wish-list', [StudentAppliedProgramController::class, 'studentWishListAdd']);
        Route::get('get-student-wish-list', [StudentAppliedProgramController::class, 'getStudentWishList']);
        // Route::put('student-program-update', [StudentAppliedProgramController::class, 'programUpdate']);
    });
    // Route::resource('countries', CountriesController::class);


    Route::get('public-countries', [CountriesController::class, 'index']);
    Route::get('public-country-data', [CountriesController::class, 'getCountryData']);
    Route::get('public-country-faqs',[ CountryFagController::class, 'index']);


    Route::get('public-currency', [CurrenciesController::class, 'index']);

    Route::get('public-state', [StatesController::class, 'index']);
    Route::get('public-state-faqs', [StateFaqsController::class, 'index']);

    Route::get('public-cities', [CitiesController::class, 'index']);
    Route::get('public-city-faqs', [CityFaqsController::class, 'index']);

    Route::get('public-institutionalType', [InstitutionalTypesController::class, 'index']);


    Route::get('public-institution', [InstitutionsController::class, 'index']);
    Route::get('public-institution-faqs', [InstitutionFaqsController::class, 'index']);

    Route::get('public-educationDegree', [EducationDegreeController::class, 'index']);

    Route::get('public-disciplines', [DisciplinesController::class, 'index']);
    Route::get('public-discipline-faqs', [DisciplineFaqsController::class, 'index']);

    Route::get('public-programs', [ProgramsController::class, 'index']);
    Route::get('public-program-data', [ ProgramsController::class, 'getProgramData']);

    Route::get('public-exams',[ExamsController::class, 'index']);

    Route::resource('match-program', MatchingProgramController::class);

    Route::post('send-confirm-email', [UserController::class, 'sendConfirmationCode']);

    Route::post('register-student', [UserController::class, 'store']);

});



