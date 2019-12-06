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

Route::post('/education', 'EducationController@create')->name('education.create');
Route::delete('/deleteEducation/{id}', 'EducationController@delete')->name('education.delete');

Route::middleware(['auth:api'])->group(function () {
});

Route::post('/job', 'JobController@create')->name('job.add');
Route::delete('/deleteJob/{id}', 'JobController@delete')->name('job.delete');
Route::post('/family', 'FamilyController@create')->name('family.add');
Route::delete('/deleteFamily/{id}', 'FamilyController@delete')->name('family.delete');
Route::post('/language', 'LanguageController@create')->name('language.create');
Route::delete('/deleteLanguage/{id}', 'LanguageController@delete')->name('language.delete');
Route::post('/achievment', 'AchievmentController@create')->name('achievment.create');
Route::delete('/deleteAchievment/{id}', 'AchievmentController@delete')->name('achievment.delete');
Route::post('/notifications', 'UserController@notification')->name('user.notification');
Route::put('/change-task-status/{id}', 'TaskController@changeStatus')->name('change.task.status');
Route::post('/addStatus', 'TaskController@addStatus')->name('add.status');
Route::put('/change-status-name/{id}', 'TaskController@changeStatusName')->name('change.status.name');
Route::delete('/delete-status/{id}', 'TaskController@deleteStatus')->name('delete.status');
Route::get('/select-task/{id}', 'TaskController@selectTask')->name('select.task');
Route::post('/start-task', 'TaskController@startTask')->name('start.task');
Route::put('/pause-task/{id}', 'TaskController@pauseTask')->name('pause.task');
Route::put('/stop-task/{id}', 'TaskController@stopTask')->name('stop.task');
Route::post('/skill', 'SkillsController@create')->name('skill.create');
Route::delete('/deleteSkill/{id}', 'SkillsController@delete')->name('delete.skill');
Route::post('/country', 'CountryController@create')->name('country.create');
Route::post('/pc', 'DivisionController@create')->name('division.create');
Route::post('/tether', 'TethersController@create')->name('tether.create');
Route::post('/process', 'ProcessController@create')->name('process.create');
Route::put('/task/mark/{id}', 'TaskController@mark')->name('task.mark');
Route::put('/process/update/{id}', 'ProcessController@update')->name('process.update');
Route::post('/fields/getFieldsList/{id}', 'FieldController@getFieldsList')->name('field.getFieldsList');
Route::get('/files/fields', 'FieldController@getFields')->name('field.getFields');
Route::post('/file/create', 'FileController@create')->name('file.create');
