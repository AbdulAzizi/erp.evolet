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
    Route::get('/', function () {
        return redirect('/tasks');
    });

    Route::get('/company/structure', 'HomeController@company')->name('company');

    Route::get('/tasks', 'TaskController@index')->name('tasks.index');
    Route::post('/tasks', 'TaskController@store')->name('tasks.store');
    Route::get('/tasks/{id}', 'TaskController@show')->name('tasks.show')->middleware('task.ownership');
    Route::put('/tasks/{id}', 'TaskController@update')->name('tasks.update');

    Route::get('/division', 'DivisionController@show')->name('division.single'); //FIXME Maybe convert to /divisions
    Route::post('/divisions', 'DivisionController@store')->name('division.store');

    Route::get('/positions', 'PositionController@index')->name('position.index');
    
    Route::get('/profile', 'UserController@show')->name('profile');
    Route::get('/users', 'UserController@index')->name('users.index');
    Route::post('/users', 'UserController@store')->name('users.store');
    Route::get('/users/{id}', 'UserController@show')->name('users.show');
    Route::get('/users/{id}/cv', 'ResumeController@show')->name('resume.show');
    Route::post('/users/{id}/cv', 'ResumeController@store')->name('resume.store');
    Route::get('/users/{id}/position', 'PositionController@show')->name('position.show');
    Route::post('/users/{id}/position', 'PositionController@store')->name('position.store');
    Route::post('/users/{id}/create-job-description', 'PositionController@createJobDescription')->name('position.create.job.description');
    Route::get('/users/{id}/cv/edit', 'ResumeController@showEdit')->name('resume.show.edit');
    Route::get('/profile/tasks', 'UserController@tasks')->name('profile.tasks');
    
    Route::get('/products', 'ProductController@index')->name('products.index');
    Route::get('/products/create', 'ProductController@create')->name('products.create');
    Route::post('/products', 'ProductController@store')->name('products.store');
    
    Route::post('/products/{productID}/changeToNext', 'ProductController@changeProcess')->name('products.changeToNext');
    Route::post('/products/{productID}/changeTo/{processID}', 'ProductController@changeProcess')->name('products.changeTo.post');
    Route::get('/products/{productID}/changeTo/{processID}', 'ProductController@changeProcess')->name('products.changeTo.get');
    
    Route::get('/products/{id}', 'ProductController@show')->name('products.show');
    
    // Route::get('/processes/{id}', 'ProcessController@show')->name('processes.show');
    
    Route::get('/projects', 'ProjectController@index')->name('projects.index');
    Route::post('/projects', 'ProjectController@store')->name('projects.store');
    Route::get('/projects/create', 'ProjectController@create')->name('projects.create');

    Route::get('/relation-data', 'ListRelationsController@getRelatedData')->name('web-utils.relationFilter');
    
    Route::get('/bp', 'ProcessController@index')->name('bp');

    Route::post('/resume', 'ResumeController@create')->name('resume.create');
    Route::get('/resume/index', 'ResumeController@index')->name('resume.index');
    Route::get('/resume/{id}', 'ResumeController@showSingle')->name('show.single');
    Route::get('/head-resumes', 'ResumeController@hrShowResumes')->name('head.resumes');
    Route::get('/resume-pdf/{id}', 'ResumeController@pdf')->name('resume.pdf');
    
    Route::get('/human-resources', 'HumanResourcesController@index')->name('human.resources');
    Route::get('/human-resources/resumes', 'HumanResourcesController@showResumes')->name('human.resources.resume');

    Route::get('/chats', 'ChatController@index')->name('chats.index');
    Route::post('/chats', 'ChatController@store')->name('chats.store');

    Route::get('/products/{id}/edit', 'ProductController@edit')->name('product.edit');

    Route::get('/deleteProcess/{id}', 'ProcessController@delete')->name('process.delete');

    Route::get('/forms', 'FormController@index')->name('forms.index');
    Route::get('/forms/{id}', 'FormController@show')->name('forms.show');

    Route::get('/tether/delete/{id}', 'TethersController@delete')->name('tether.delete');
    
    Route::prefix('admin')->group(function () {
        Route::get('/products', 'ProductController@adminIndex')->name('admin.products');
        Route::get('/files', 'FileController@index')->name('admin.files.index');
    });
    
    Route::get('/factories/create', 'FactoryController@create')->name('factories.create');
    Route::get('/factories', 'FactoryController@index')->name('factories.index');
    Route::get('/factories/{id}', 'FactoryController@show')->name('factories.show');
    Route::post('/factories', 'FactoryController@store')->name('factories.store');
});



Route::prefix('api')->group(function () {
    // later must go API
    Route::post('/polls', 'PollController@storeApi')->name('api.polls.store');
    Route::post('/messages', 'MessageController@storeApi')->name('api.messages.store');
    Route::get('/chats/{id}/details', 'ChatController@getDetails')->name('api.getDetails');
    Route::get('/directs/{userID}', 'ChatController@getdirect')->name('api.getDirect');
    Route::get('/get/products', 'ProductController@getProducts')->name('get.products');
    Route::get('/lists/{id}/items', 'ListController@items')->name('api.lists.items');
    Route::put('/fields/edit/{id}', 'FieldController@edit')->name('fields.edit');
    Route::get('/fieldtypes', 'FieldController@getFieldTypes')->name('api.getFieldTypes');
});
