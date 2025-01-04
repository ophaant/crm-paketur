<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ManagerController;
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
    Route::post('logout', 'logout')->name('logout')->middleware('auth:api');
    Route::post('refresh', 'refresh')->name('refresh')->middleware('auth:api');
});


Route::middleware(['auth:api'])->group(function(){
    Route::controller(EmployeeController::class)->group(function(){
        Route::get('employees', 'index');
        Route::post('employees', 'store');
        Route::get('employees/{id}', 'show');
        Route::put('employees/{id}', 'update')->where('id', '[0-9]+');
        Route::delete('employees/{id}', 'destroy');
    });

    Route::controller(ManagerController::class)->group(function(){
        Route::get('managers', 'index');
        Route::post('managers', 'store');
        Route::get('managers/{id}', 'show');
        Route::put('managers/{id}', 'update')->where('id', '[0-9]+');
        Route::delete('managers/{id}', 'destroy');
    });

    Route::controller(CompanyController::class)->group(function(){
        Route::get('companies', 'index');
        Route::post('companies', 'store');
        Route::get('companies/{id}', 'show');
        Route::put('companies/{id}', 'update');
        Route::delete('companies/{id}', 'destroy');
    });
});

