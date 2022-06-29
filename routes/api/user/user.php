<?php
Route::get('/user/all', 'Api\UserController@all')->name('user.all');
Route::post('/user/create', 'Api\UserController@create')->name('user.create');
Route::get('/user/find/{id}', 'Api\UserController@find')->name('user.find');
Route::patch('/user/update/{id}', 'Api\UserController@update')->name('user.update');
Route::get('/user/destroy/{id}', 'Api\UserController@destroy')->name('user.destroy');
Route::get('/user/restore/{id}', 'Api\UserController@restore')->name('user.restore');
Route::get('/user/permission/{permission}', 'Api\UserController@getByPermission')->name('user.permission');
Route::get('user/showlogued/', 'Api\UserController@show')->name('user.showlogued');
Route::post('user/changepassword', 'Api\UserController@changePassword')->name('user.changePassword');
