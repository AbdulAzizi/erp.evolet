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

Route::post('/job', 'ResumeController@jobAdd')->name('job-add');
Route::delete('/deleteJob/{id}', 'ResumeController@jobDelete')->name('job-delete');
Route::post('/family', 'ResumeController@familyAdd')->name('family-add');
Route::delete('/deleteFamily/{id}', 'ResumeController@familyDelete')->name('family-delete');
Route::post('/language', 'ResumeController@languageAdd')->name('language-add');
Route::delete('/deleteLanguage/{id}', 'ResumeController@languageDelete')->name('language-delete');
Route::post('/achievment', 'ResumeController@achievmentAdd')->name('achievment-add');
Route::delete('/deleteAchievment/{id}', 'ResumeController@achievmentDelete')->name('achievment-delete');
Route::post('/notifications', 'UserController@notification')->name('user.notification');
Route::put('/change-task-status/{id}', 'TaskController@changeStatus')->name('change.task.status');
Route::post('/addStatus', 'TaskController@addStatus')->name('add.status');
Route::put('/change-status-name/{id}', 'TaskController@changeStatusName')->name('change.status.name');
Route::delete('/delete-status/{id}', 'TaskController@deleteStatus')->name('delete.status');
