<?php
Route::get('/project/all', 'Api\ProjectController@show')->name('project.all');
Route::post('/project/create', 'Api\ProjectController@create')->name('project.create');
Route::get('/project/edit/{id}', 'Api\ProjectController@edit')->name('project.edit');
Route::patch('/project/update/{id}', 'Api\ProjectController@update')->name('project.update');
Route::get('/project/destroy/{id}', 'Api\ProjectController@destroy')->name('project.destroy');
Route::get('/project/restore/{id}', 'Api\ProjectController@restore')->name('project.restore');
