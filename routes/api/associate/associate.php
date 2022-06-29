<?php
Route::get('/associate/all', 'Api\AssociateController@show')->name('associate.all');
Route::post('/associate/create', 'Api\AssociateController@create')->name('associate.create');
Route::get('/associate/find/{id}', 'Api\AssociateController@find')->name('associate.find');
Route::patch('/associate/update/{id}', 'Api\AssociateController@update')->name('associate.update');
Route::get('/associate/destroy/{id}', 'Api\AssociateController@destroy')->name('associate.destroy');
Route::get('/associate/restore/{id}', 'Api\AssociateController@restore')->name('associate.restore');
