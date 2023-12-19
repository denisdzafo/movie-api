<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group([
    'middleware' => 'api',
], function () {
    Route::group([
        'prefix' => 'auth',
    ], function () {
        Route::post('/login', [UserController::class, 'login']);
        Route::post('/register', [UserController::class, 'register']);

        Route::group([
            'middleware' => 'jwt.auth',
        ], function () {
            Route::get('/logout', [UserController::class, 'logout']);
            Route::get('/refresh', [UserController::class, 'refresh']);
        });
    });

    Route::group([
        'prefix' => 'movie',
    ], function () {
        Route::post('/all', [MovieController::class, 'getAllMovies']);

        Route::group([
            'middleware' => 'jwt.auth',
        ], function () {
            Route::post('/add', [MovieController::class, 'addMovieToFavourite']);
            Route::post('/remove', [MovieController::class, 'removeMovieFromFavourite']);
            Route::get('/user', [MovieController::class, 'getMyFavouriteMovies']);
            Route::post('/single', [MovieController::class, 'getSingleMovie']);
        });
    });

    Route::group([
        'prefix' => 'category',
    ], function () {
        Route::get('/all', [CategoryController::class, 'getAllCategories']);
    });
});

