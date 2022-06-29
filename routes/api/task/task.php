<?php
use App\Repositories\ReportRepository;
// Associate
Route::get('/taskassociate/all', 'Api\TaskController@showAssociate')->name('task.allassociate');
Route::get('/taskassociate/find/{id}', 'Api\TaskController@editAssociate')->name('task.editassociate');
Route::post('/taskassociate/create', 'Api\TaskController@create')->name('task.create');
Route::get('/taskassociate/delete/{id}', 'Api\TaskController@delete')->name('task.delete');
Route::post('/taskassociate/addfiles', 'Api\TaskController@addFiles')->name('task.add');
Route::post('/taskassociate/deletefile', 'Api\TaskController@dropFiles')->name('task.drop');
Route::get('/taskassociate/downloadfile', 'Api\TaskController@downloadFile')->name('task.download');
Route::post('/taskassociate/update/{id}', 'Api\TaskController@update')->name('task.update');
// Manager
Route::get('/task/all', 'Api\AdminTaskController@all')->name('task.all');
Route::get('/task/find/{id}', 'Api\AdminTaskController@find')->name('task.find');
Route::post('/task/update/{id}', 'Api\AdminTaskController@update')->name('task.updatemanager');
Route::get('/task/report', 'Api\AdminTaskController@getReport')->name('task.report');
