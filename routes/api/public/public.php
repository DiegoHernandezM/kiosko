<?php
Route::post('/login', 'Api\AuthController@login')->name('login.api');
Route::post('/resetpassword', 'Api\AuthController@resetPassword')->name('reset.api');
Route::post('/submitresetpassword', 'Api\AuthController@submitResetPassword')->name('submitreset.api');
Route::get('v1/inquest/recipient/verify/{uuid}', 'Api\InquestController@verify')->name('inquest.verify');
Route::get('v1/inquest/recipient/{uuid}', 'Api\InquestController@getByUuid')->name('inquest.get');
Route::post('v1/inquest/send/answers', 'Api\AnswerController@create')->name('answer.send');
Route::get('/verify', 'Api\AuthController@verifyemail')->name('user.verify');
