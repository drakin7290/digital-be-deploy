<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'api',
    'prefix'     => 'api/v1',
    'namespace'  => 'Botble\Customers\Http\Controllers\API',
], function () {
    Route::prefix('customer')->group(function () {
        Route::post('login', 'CustomerController@login');
        Route::post('register', 'CustomerController@register');
        Route::post('logout', 'AuthController@logout');
        Route::post('attendance', 'AuthController@attendance');
        Route::get('list_attendance', 'AuthController@get_list_attendance');
        Route::get('streak', 'AuthController@streak');
    });
});