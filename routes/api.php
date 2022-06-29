<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['json.response']], function () {

    // public routes
    require base_path('routes/api/public/public.php');

    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });

    // private routes
    Route::middleware('auth:api')->group(function () {
        Route::get('/logout', 'Api\AuthController@logout')->name('logout');
    });
});

Route::group(
    [
        'middleware' => ['auth:api'],
        'prefix'     => '/v1'
    ],
    function () {
        Route::get('/user', 'Api\UserController@show')->name('user.show');


        require base_path('routes/api/project/project.php');

        require base_path('routes/api/area/area.php');

        require base_path('routes/api/subarea/subarea.php');

        require base_path('routes/api/associate/associate.php');

        require base_path('routes/api/user/user.php');

        require base_path('routes/api/petition/petition.php');

        require base_path('routes/api/task/task.php');

        require base_path('routes/api/dashboard/dashboard.php');

        require base_path('routes/api/inquest/inquest.php');

        require base_path('routes/api/recipient/recipient.php');

        require base_path('routes/api/answer/answer.php');

        require base_path('routes/api/objective/objective.php');

        require base_path('routes/api/event/event.php');
    }
);
