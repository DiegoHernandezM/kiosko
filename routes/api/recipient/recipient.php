<?php
Route::get('/recipient/all', 'Api\RecipientController@show')->name('recipient.all');
Route::post('/recipient/create', 'Api\RecipientController@create')->name('recipient.create');
Route::get('/recipient/edit/{id}', 'Api\RecipientController@edit')->name('recipient.edit');
Route::patch('/recipient/update/{id}', 'Api\RecipientController@update')->name('recipient.update');
Route::patch('/recipient/destroy/{id}', 'Api\RecipientController@destroy')->name('recipient.destroy');
Route::patch('/recipient/restore/{id}', 'Api\RecipientController@restore')->name('recipient.restore');
