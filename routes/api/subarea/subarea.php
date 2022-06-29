<?php
Route::get('/subarea/all', 'Api\SubareaController@show')->name('subarea.all');
Route::post('/subarea/create', 'Api\SubareaController@create')->name('subarea.create');
Route::get('/subarea/edit/{id}', 'Api\SubareaController@edit')->name('subarea.edit');
Route::patch('/subarea/update/{id}', 'Api\SubareaController@update')->name('subarea.update');
Route::patch('/subarea/destroy/{id}', 'Api\SubareaController@destroy')->name('subarea.destroy');
Route::patch('/subarea/restore/{id}', 'Api\SubareaController@restore')->name('subarea.restore');
Route::get('/subarea/area/{area}', 'Api\SubareaController@showByArea')->name('subarea.area');
