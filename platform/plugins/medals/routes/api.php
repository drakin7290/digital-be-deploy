<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'api',
    'prefix'     => 'api/v1',
    'namespace'  => 'Botble\Medals\Http\Controllers\API',
], function () {
    Route::prefix('medal')->group(function () {
        Route::get('list', 'MedalController@getList')->name('getList');
    });
});
