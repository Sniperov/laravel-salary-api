<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['namespace'  => 'App\Http\Controllers\Api', 'prefix' => '/'], function () {
    Route::get('/calculate', 'SalaryController@getCalculatedSalary');
    Route::post('/store', 'SalaryController@storeCalculatedSalary');
});
