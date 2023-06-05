<?php

Route::group(['namespace' => 'Botble\Medals\Http\Controllers', 'middleware' => ['web', 'core']], function () {

    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {

        Route::group(['prefix' => 'medals', 'as' => 'medals.'], function () {
            Route::resource('', 'MedalsController')->parameters(['' => 'medals']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'MedalsController@deletes',
                'permission' => 'medals.destroy',
            ]);
        });
    });

});
