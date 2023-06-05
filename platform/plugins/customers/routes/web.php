<?php

Route::group(['namespace' => 'Botble\Customers\Http\Controllers', 'middleware' => ['web', 'core']], function () {

    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {

        Route::group(['prefix' => 'customers', 'as' => 'customers.'], function () {
            Route::resource('', 'CustomersController')->parameters(['' => 'customers']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'CustomersController@deletes',
                'permission' => 'customers.destroy',
            ]);
        });
    });

});
