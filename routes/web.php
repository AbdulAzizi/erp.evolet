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
    Route::get('/profile', 'EmployeeController@show')->name('profile.current');
    Route::get('/profile/{id}', 'EmployeeController@show')->name('profile.show');
    Route::get('/division', 'DivisionController@show')->name('division'); //FIXME Maybe convert to /divisions
    
    Route::post('/employees', 'EmployeeController@store')->name('employees.store');

});