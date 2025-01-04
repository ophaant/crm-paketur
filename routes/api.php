<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ManagerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'rc' => '0200',
        'success' => true,
        'message' => 'Welcome to the CRM API'
    ]);
});

Route::controller(AuthenticationController::class)->group(function(){
    Route::post('login', 'login')->name('login');
});

//modifying using middleware auth:api

Route::middleware(['auth:api'])->group(function(){
    Route::controller(EmployeeController::class)->group(function(){
        Route::get('employees', 'index');
        Route::post('employees', 'store');
        Route::get('employees/{id}', 'show');
        Route::put('employees/{id}', 'update');
        Route::delete('employees/{id}', 'destroy');
    });

    Route::controller(ManagerController::class)->group(function(){
        Route::get('managers', 'index');
        Route::post('managers', 'store');
        Route::get('managers/{id}', 'show');
        Route::put('managers/{id}', 'update');
        Route::delete('managers/{id}', 'destroy');
    });
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
