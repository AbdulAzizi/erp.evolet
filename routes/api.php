<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::group(['middleware'=>['']],function(){

// });
Route::get('/mnns', 'DataController@mnns')->name('mnns');
Route::get('/forms', 'DataController@forms')->name('forms');

// Add resume information

Route::post('/education', 'ResumeController@educationAdd')->name('education-add');
Route::delete('/deleteEducation/{id}', 'ResumeController@educationDelete')->name('education-delete');
Route::put('/edit/education/{id}', 'ResumeController@educationEdit')->name('education-edit');


Route::middleware(['auth:api'])->group(function () {
});
Route::post('/polls','PollController@storeApi')->name('api.polls.store');
