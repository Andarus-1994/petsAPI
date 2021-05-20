<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FirebaseController;
use App\Http\Controllers\PetsController;

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

Route::post('/getPetDetails', [PetsController::class, 'getPetDetails']);
Route::get('/getAllPets', [PetsController::class, 'getAllPets']);
Route::get('/getToken', [PetsController::class, 'getToken']);
Route::post('/addPost', [FirebaseController::class, 'addPost']);
Route::post('/getPosts', [FirebaseController::class, 'getPosts']);
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
