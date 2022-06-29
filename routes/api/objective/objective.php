<?php
// Associate
Route::get('/objective/all', 'Api\ObjectiveController@show')->name('objective.all');
Route::get('/objective/find/{id}', 'Api\ObjectiveController@edit')->name('objective.edit');
Route::post('/objective/create', 'Api\ObjectiveController@create')->name('objective.create');
Route::get('/objective/delete/{id}', 'Api\ObjectiveController@delete')->name('objective.delete');
Route::post('/objective/addfiles', 'Api\ObjectiveController@addFiles')->name('objective.add');
Route::post('/objective/deletefile', 'Api\ObjectiveController@dropFiles')->name('objective.drop');
Route::get('/objective/downloadfile', 'Api\ObjectiveController@downloadFile')->name('objective.download');
Route::post('/objective/update/{id}', 'Api\ObjectiveController@update')->name('objective.update');
// Manager
Route::get('/objective/allassociates', 'Api\ObjectiveController@allAssociates')->name('objective.allassociates');
