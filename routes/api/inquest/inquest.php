<?php
Route::get('/inquest/all', 'Api\InquestController@show')->name('inquest.all');
Route::post('/inquest/create', 'Api\InquestController@create')->name('inquest.create');
Route::get('/inquest/edit/{id}', 'Api\InquestController@edit')->name('inquest.edit');
Route::patch('/inquest/update/{id}', 'Api\InquestController@update')->name('inquest.update');
Route::patch('/inquest/destroy/{id}', 'Api\InquestController@destroy')->name('inquest.destroy');
Route::patch('/inquest/restore/{id}', 'Api\InquestController@restore')->name('inquest.restore');
Route::post('/inquest/changestatus/{id}', 'Api\InquestController@changeStatusInquest')->name('inquest.changestatus');
Route::get('/inquest/csv/{id}', 'Api\InquestController@getInquestCsv')->name('inquest.getcsv');
