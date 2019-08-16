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



Route::group(['middleware'=>['auth', 'data.default']],function(){

    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/', function () {
        return redirect('/tasks');
    });

    Route::get('/company/structure', 'HomeController@company')->name('company');

    Route::get('/tasks', 'TaskController@index')->name('tasks.index');
    Route::post('/tasks', 'TaskController@store')->name('tasks.store');
    Route::get('/tasks/{id}', 'TaskController@show')->name('tasks.show');

    Route::get('/profile', 'UserController@show')->name('profile');
    Route::get('/division', 'DivisionController@show')->name('division.single'); //FIXME Maybe convert to /divisions
    Route::post('/divisions', 'DivisionController@store')->name('division.store');

    Route::post('/users', 'UserController@store')->name('users.store');
    Route::get('/users/{id}', 'UserController@show')->name('users.show');

    Route::get('/products','ProductController@index')->name('products.index');
    Route::get('/products/create','ProductController@create')->name('products.create');
    Route::post('/products','ProductController@store')->name('products.store');

    Route::get('/processes/{id}','ProcessController@show')->name('processes.show');

    Route::get('/projects','ProjectController@index')->name('projects.index');
    Route::get('/relation-data', 'ListRelationsController@getRelatedData')->name('web-utils.relationFilter');

    Route::get('/bp', 'ProcessController@index')->name('bp');
    Route::get('/profile/tasks', 'UserController@tasks')->name('profile-tasks');
    Route::get('/users/{id}/cv', 'ResumeController@show')->name('resume-show');
    Route::post('/users/{id}/cv', 'ResumeController@store')->name('resume-store');


});
