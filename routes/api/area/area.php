<?php
Route::get('/area/all', 'Api\AreaController@show')->name('area.all');
Route::post('/area/create', 'Api\AreaController@create')->name('area.create');
Route::get('/area/edit/{id}', 'Api\AreaController@edit')->name('area.edit');
Route::patch('/area/update/{id}', 'Api\AreaController@update')->name('area.update');
Route::patch('/area/destroy/{id}', 'Api\AreaController@destroy')->name('area.destroy');
Route::patch('/area/restore/{id}', 'Api\AreaController@restore')->name('area.restore');
