<?php
Route::get('/event/all', 'Api\EventController@all')->name('event.all');
Route::post('/event/create', 'Api\EventController@create')->name('event.create');
Route::post('/event/update/{id}', 'Api\EventController@update')->name('event.update');
Route::post('/event/delete/{id}', 'Api\EventController@delete')->name('event.delete');
