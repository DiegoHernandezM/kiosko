<?php
Route::get('/petition/all', 'Api\PetitionController@show')->name('petition.all');
Route::get('/petition/allbyassociate', 'Api\PetitionController@allByAssociate')->name('petition.allbyassociate');
Route::post('/petition/create', 'Api\PetitionController@create')->name('petition.create');
Route::get('/petition/edit/{id}', 'Api\PetitionController@edit')->name('petition.edit');
Route::patch('/petition/update/{id}', 'Api\PetitionController@update')->name('petition.update');
Route::post('/petition/approved/{id}', 'Api\PetitionController@approvedPetition')->name('petition.approved');
Route::patch('/petition/destroy/{id}', 'Api\PetitionController@destroy')->name('petition.destroy');
Route::patch('/petition/restore/{id}', 'Api\PetitionController@restore')->name('petition.restore');
Route::post('/petition/addfiles', 'Api\PetitionController@addFiles')->name('petition.add');
Route::post('/petition/deletefile', 'Api\PetitionController@dropFiles')->name('petition.drop');
Route::get('/petition/downloadfile', 'Api\PetitionController@downloadFile')->name('petition.download');
