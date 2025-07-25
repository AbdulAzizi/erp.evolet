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
Route::post('/hobbies', 'HobbieController@create')->name('hobbies.create');
Route::delete('/hobbies/{id}', 'HobbieController@delete')->name('hobbies.delete');
Route::post('/extraInformations', 'ExtrainformationController@create')->name('information.create');
Route::delete('/extraInformations/{id}', 'ExtrainformationController@delete')->name('information.delete');
Route::post('/mark-notifications', 'UserController@markAsRead')->name('user.markAsRead');
Route::put('/change-task-status/{id}', 'TaskController@changeStatus')->name('change.task.status');
Route::post('/addStatus', 'TaskController@addStatus')->name('add.status');
Route::put('/change-status-name/{id}', 'TaskController@changeStatusName')->name('change.status.name');
Route::delete('/delete-status/{id}', 'TaskController@deleteStatus')->name('delete.status');
Route::get('/select-task/{id}', 'TaskController@selectTask')->name('select.task');
Route::post('/skill', 'SkillsController@create')->name('skill.create');
Route::delete('/deleteSkill/{id}', 'SkillsController@delete')->name('delete.skill');
Route::post('/country', 'CountryController@create')->name('country.create');
Route::post('/pc', 'DivisionController@create')->name('division.create');
Route::post('/tether', 'TethersController@create')->name('tether.create');
Route::post('/process', 'ProcessController@create')->name('process.create');
Route::put('/task/mark/{id}', 'TaskController@mark')->name('task.mark');
Route::put('/process/update/{id}', 'ProcessController@update')->name('process.update');
Route::post('/fields/getFieldsList/{id}', 'FieldController@getFieldsList')->name('field.getFieldsList');
Route::get('/fields', 'FieldController@getFields')->name('field.getFields');
Route::get('/files/fields/notExisitng/{id}', 'FieldController@getOnlyNotExistingFields')->name('field.getOnlyNotExistingFields');
Route::post('/file/create', 'FileController@create')->name('file.create');
Route::delete('/files/delete/{id}', 'FileController@delete')->name('file.delte');
Route::post('/files/field/delete/{id}', 'FileController@deleteFieldFromFile')->name('file.deleteFieldFromFile');
Route::post('/files/fields/create', 'FileController@attachFields')->name('file.attachFields');
Route::post('/files/update/{id}', 'FileController@update')->name('file.update');
Route::post('/process/tasks/create', 'ProcessTaskController@create')->name('processTask.create');
Route::get('/positions', 'PositionController@loadPositions')->name('position.loadPositions');
Route::get('/division/positions/{id}', 'PositionLevelController@loadDivisionPositions')->name('position.loadDivisionPositions');
Route::get('/positionLevels', 'PositionLevelController@loadPositionLevels')->name('positionLevel.loadPositionLevels');
Route::delete('/process/task/delete/{id}', 'ProcessTaskController@delete')->name('processTask.delete');
Route::put('/process/task/edit/{id}', 'ProcessTaskController@edit')->name('processTask.edit');
Route::post('/forms/create', 'FormController@create')->name('form.create');
Route::get('/get/forms', 'FormController@getForms')->name('forms.index');
Route::delete('/forms/delete/{id}', 'FormController@delete')->name('form.delete');
Route::post('/process/task/add/form', 'ProcessTaskController@addForm')->name('processTask.addForm');
Route::get('/field/type', 'FieldTypeController@index')->name('fieldType.index');
Route::post('/field/create', 'FieldController@create')->name('field.create');
Route::post('/field/delete/{id}', 'FormController@deleteField')->name('field.deleteField');
Route::post('/mark/all/notifications/{id}', 'UserController@markAllNotificationsAsRead')->name('user.markAllNotificationsAsRead');
Route::post('/create/position', 'PositionController@store')->name('position.store');
Route::post('/add/single/position/{id}', 'PositionController@addPosition')->name('position.add');
Route::post('/edit/position/{id}', 'PositionController@edit')->name('position.edit');
Route::delete('/delete/position/{id}', 'PositionController@delete')->name('position.delete');
Route::delete('/delete/description/{id}', 'ResponsibilityController@delete')->name('responsibility.delete');
Route::post('/edit/responsibility/{id}', 'ResponsibilityController@edit')->name('responsibility.edit');
Route::post('/add/responsibility', 'ResponsibilityController@create')->name('responsibility.create');
Route::delete('/delete/responsibility/{id}', 'ResponsibilityController@delete')->name('responsibility.delete');
Route::post('/add/responsibility/description', 'ResponsibilityDescriptionController@create')->name('responsibilityDescription.create');
Route::post('/edit/responsibility/description/{id}', 'ResponsibilityDescriptionController@edit')->name('responsibilityDescription.edit');
Route::delete('/delete/responsibility/description/{id}', 'ResponsibilityDescriptionController@delete')->name('responsibilityDescription.delete');
Route::get('/divisions', 'DivisionController@loadDivisions')->name('division.load');


Route::post('/divisions/create', 'DivisionController@store')->name('division.store');
Route::delete('/divisions/{id}', 'DivisionController@delete')->name('division.delete');

Route::post('/tags/create', 'TagController@create')->name('tag.create');
Route::delete('/tags/{id}', 'TagController@delete')->name('tag.delete');

Route::get('/divisions/{id}/tags', 'DivisionController@tags')->name('division.tags');

Route::post('/users/{id}/responsibilities', 'ResponsibilityController@attachUser')->name('responsibilities.attachUser');
Route::delete('/users/{userID}/positions/{positionID}', 'UserController@detachPosition')->name('users.detachPosition');
Route::post('/users', 'UserController@store')->name('users.store');
Route::delete('/users/{id}', 'UserController@delete')->name('users.delete');
Route::post('/users/responsibilities', 'UserController@responsibilities')->name('api.users.responsibilities');
Route::get('/users/{id}/responsibilitydescriptions', 'UserController@responsibilitydescriptions')->name('api.users.responsibilitydescriptions');

Route::post('/divisions/{id}/edit', 'DivisionController@edit')->name('divisions.edit');

Route::delete('/tasks/{id}', 'TaskController@delete')->name('api.tasks.delete');

Route::get('/statuses', 'StatusController@all')->name('api.statuses.all');

Route::post('/resume/{id}/edit', 'ResumeController@edit')->name('api.resume.edit');



