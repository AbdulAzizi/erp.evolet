<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();



Route::group(['middleware' => ['auth', 'data.default']], function () {

    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/', function () {
        return redirect('/tasks');
    });

    Route::get('/company/structure', 'HomeController@company')->name('company');

    Route::get('/tasks', 'TaskController@index')->name('tasks.index');
    Route::post('/tasks', 'TaskController@store')->name('tasks.store');
    Route::get('/tasks/{id}', 'TaskController@show')->name('tasks.show');
    Route::put('/tasks/{id}', 'TaskController@update')->name('tasks.update');

    Route::get('/profile', 'UserController@show')->name('profile');
    Route::get('/division', 'DivisionController@show')->name('division.single'); //FIXME Maybe convert to /divisions
    Route::post('/divisions', 'DivisionController@store')->name('division.store');

    Route::get('/users', 'UserController@index')->name('users.index');
    Route::post('/users', 'UserController@store')->name('users.store');
    Route::get('/users/{id}', 'UserController@show')->name('users.show');

    Route::get('/products', 'ProductController@index')->name('products.index');
    Route::get('/products/create', 'ProductController@create')->name('products.create');
    Route::post('/products', 'ProductController@store')->name('products.store');

    Route::get('/processes/{id}', 'ProcessController@show')->name('processes.show');

    Route::get('/projects', 'ProjectController@index')->name('projects.index');
    Route::post('/projects', 'ProjectController@store')->name('projects.store');
    Route::get('/projects/create', 'ProjectController@create')->name('projects.create');

    Route::get('/relation-data', 'ListRelationsController@getRelatedData')->name('web-utils.relationFilter');

    Route::get('/bp', 'ProcessController@index')->name('bp');
    Route::get('/profile/tasks', 'UserController@tasks')->name('profile.tasks');

    Route::get('/users/{id}/cv', 'ResumeController@show')->name('resume.show');
    Route::post('/users/{id}/cv', 'ResumeController@store')->name('resume.store');

    Route::get('/users/{id}/responsibility', 'ResponsibilityController@show')->name('responsibility.show');
    Route::post('/users/{id}/responsibility', 'ResponsibilityController@store')->name('responsibility.store');
    Route::post('/users/{id}/create-job-description', 'ResponsibilityController@createJobDescription')->name('responsibility.create.job.description');

    Route::get('/users/{id}/cv/edit', 'ResumeController@showEdit')->name('resume.show.edit');
    Route::post('/resume', 'ResumeController@create')->name('resume.create');
    Route::get('/resume/index', 'ResumeController@index')->name('resume.index');
    Route::get('/resume/{id}', 'ResumeController@showSingle')->name('show.single');
    Route::get('/head-resumes', 'ResumeController@headResumes')->name('head.resumes');
    Route::get('/resume-pdf/{id}', 'ResumeController@pdf')->name('resume.pdf');

    Route::get('/human-resources', 'HumanResourcesController@index')->name('human.resources');
    Route::get('/human-resources/resumes', 'HumanResourcesController@showResumes')->name('human.resources.resume');

    Route::get('/chats', 'ChatController@index')->name('chats.index');
    Route::post('/chats', 'ChatController@store')->name('chats.store');


});

// later must go API
Route::post('/polls', 'PollController@storeApi')->prefix('api')->name('api.polls.store');
Route::post('/comments', 'CommentController@storeApi')->prefix('api')->name('api.comments.store');
Route::get('/chats/{id}/details', 'ChatController@getDetails')->prefix('api')->name('api.getDetails');
Route::get('/conversations/{userID}', 'ChatController@getconversation')->prefix('api')->name('api.getConversation');