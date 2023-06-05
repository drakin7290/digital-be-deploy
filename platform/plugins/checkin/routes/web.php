<?php

Route::group(['namespace' => 'Botble\Checkin\Http\Controllers', 'middleware' => ['web', 'core']], function () {

    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {

        Route::group(['prefix' => 'checkins', 'as' => 'checkin.'], function () {
            Route::resource('', 'CheckinController')->parameters(['' => 'checkin']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'CheckinController@deletes',
                'permission' => 'checkin.destroy',
            ]);
        });
    });

});
