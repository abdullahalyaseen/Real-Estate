<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FlatController;
use App\Http\Controllers\Api\MarkerController;
use App\Http\Controllers\Api\PhotoController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\RepresController;
use App\Http\Controllers\Api\TripController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VideoController;
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

Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {

    //For common purpose operations ----- start
    Route::get('all-locations', [MarkerController::class, 'getAllMarkers']);
    Route::get('all-projects', [ProjectController::class, 'getAllProjects']);
    Route::get('project/{id}', [ProjectController::class, 'getProjectById']);
    //For common purpose operations ----- end

    //For only-admin operations ----- start
    Route::group(['middleware' => ['isadmin']], function () {
        //User operations-------------start
        Route::get('all-users', [UserController::class, 'getAllUsers']);
        Route::get('user/{id}', [UserController::class, 'getUser']);
        Route::post('new-user', [UserController::class, 'createUser']);
        Route::post('update-user/{id}', [UserController::class, 'updateUser']);
        Route::delete('delete-user/{id}', [UserController::class, 'deleteUser']);
        //User operations-------------end

        //Project operations-------------start
        Route::post('new-project', [ProjectController::class, 'createProject']);
        Route::delete('delete-project/{id}', [ProjectController::class, 'deleteProject']);
        Route::post('update-project', [ProjectController::class, 'updateProject']);
            //Representative operations-------------start
            Route::post('update-repres/{id}', [RepresController::class, 'updateRepres']);
            //Representative operations-------------end
            //Marker operations-------------start
            Route::post('update-marker/{id}',[MarkerController::class,'updateMarker']);
            //Marker operations-------------end
            //Photos operations-------------start
            Route::post('project/add-photos', [ProjectController::class, 'addProjectPhotos']);
            Route::delete('photos/{id}', [PhotoController::class, 'deletePhoto']);
            //Photos operations-------------end
            //Video operations-------------start
            Route::post('project/add-videos', [ProjectController::class, 'addProjectVideos']);
            Route::delete('videos/{id}', [VideoController::class, 'deleteVideo']);
            //Video operations-------------end
            //Flats operations-------------start
            Route::get('flat-types', [FlatController::class, 'getFlatTypes']);
            Route::post('add-flat',[FlatController::class,'addFlat']);
            Route::delete('delete-flat/{id}',[FlatController::class,'deleteFlat']);
            //Flats operations-------------end
        //Project operations-------------end

        //Trips operations-------------start
        Route::post('new-trip',[TripController::class,'newTrip']);
        Route::get('all-trips', [TripController::class, 'getAllTrips']);
        //Trips operations-------------end

    });
    //For only-admin operations ----- end
});




