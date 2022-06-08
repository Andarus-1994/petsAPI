<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FirebaseController;
use App\Http\Controllers\PetsController;
use App\Http\Controllers\StrategyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FeedbackController;

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
Route::get('/getFeedback', [FeedbackController::class, 'getFeedback']);
Route::post('/addFeedback', [FeedbackController::class, 'addFeedback']);
Route::get('/getPets', [PetsController::class, 'getPets']);
Route::post('/getPetDetails', [PetsController::class, 'getPetDetails']);
Route::get('/getAllPets', [PetsController::class, 'getAllPets']);
Route::post('/getSearchedPet', [PetsController::class, 'getSearchedPet']);
Route::get('/getToken', [PetsController::class, 'getToken']);
Route::post('/addPost', [FirebaseController::class, 'addPost']);
Route::post('/getPosts', [FirebaseController::class, 'getPosts']);
Route::post('/addStrategy', [StrategyController::class, 'addStrategy']);
Route::post('/getStrategy', [StrategyController::class, 'getStrategies']);
Route::post('/getInstructions', [StrategyController::class, 'getInstructions']);
Route::post('/validateUser', [UserController::class, 'validateUser']);
Route::post('/createUser', [UserController::class, 'register']);
Route::post('/loginUser', [UserController::class, 'login']);
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
