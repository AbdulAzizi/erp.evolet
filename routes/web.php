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
    Route::get('/profile', 'UserController@show')->name('profile');
    Route::get('/users/{id}', 'UserController@show')->name('user.show');
    Route::get('/division', 'DivisionController@show')->name('division.single'); //FIXME Maybe convert to /divisions
    Route::post('/divisions', 'DivisionController@store')->name('division.store');

    Route::post('/users', 'UserController@store')->name('user.store');

    Route::get('/products','ProductController@index')->name('products.index');
    Route::post('/products','ProductController@store')->name('products.store');
    
    Route::get('/projects','ProjectController@index')->name('projects.index');

});