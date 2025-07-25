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
    Route::get('/', 'HomeController@home')->name('home');
    Route::get('/company/structure', 'HomeController@company')->name('company');

    Route::get('/tasks', 'TaskController@index')->name('tasks.index');
    Route::get('/tasks/{id}', 'TaskController@show')->name('tasks.show')->middleware('task.ownership');
    Route::post('/tasks/{id}/forward', 'TaskController@forward')->name('tasks.forward');

    Route::get('/division', 'DivisionController@show')->name('division.single'); //FIXME Maybe convert to /divisions

    Route::get('/users/fired', 'UserController@fired')->name('users.fired');
    
    Route::group(['middleware' => 'profile'], function () {
        Route::get('/users/{id}/tasks', 'ProfileController@tasks')->name('profile.tasks');
        Route::get('/users/{id}/positions', 'ProfileController@positions')->name('profile.positions');
        Route::get('/users/{id}', 'ProfileController@dashboard')->name('users.dashboard');
        Route::get('/users/{id}/setTasks', 'ProfileController@setTasks')->name('users.setTasks');
        Route::get('/users/{id}/entries', 'ProfileController@entries')->name('users.entries');
        Route::get('/users/{id}/facilities', 'FacilityController@index')->name('users.facilities');
    });

    Route::get('/profile', 'UserController@show')->name('profile');
    Route::get('/users', 'UserController@index')->name('users.index');

    Route::post('/users', 'UserController@store')->name('users.store');
    Route::get('/users/{id}/cv', 'ResumeController@show')->name('resume.show');
    Route::post('/users/{id}/cv', 'ResumeController@store')->name('resume.store');
    // Route::post('/users/{id}/position', 'PositionController@store')->name('position.store');
    Route::post('/users/{id}/create-job-description', 'PositionController@createResponsibility')->name('position.create.job.description');
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

    Route::group(['prefix' => 'hr', 'middleware' => 'hr'], function () {
        Route::get('/positions', 'PositionController@index')->name('position.index');
        Route::get('/entries', 'EntryController@index')->name('entries.index');
        Route::post('/entries', 'EntryController@store')->name('entries.store');
    });

    Route::get('/factories/create', 'FactoryController@create')->name('factories.create');
    Route::get('/factories', 'FactoryController@index')->name('factories.index');
    Route::get('/factories/{id}', 'FactoryController@show')->name('factories.show');
    Route::post('/factories', 'FactoryController@store')->name('factories.store');
    Route::post('/users/change/avatar/{id}', 'UserController@changeAvatar')->name('user.changeAvatar');
    Route::post('/users/{id}/fire', 'UserController@fire')->name('users.fire');
    
    Route::get('/timesets', 'TimesetController@index')->name('timesets.index');

    Route::get('/users-tasks', 'UserController@tasks')->name('users.tasks');
    Route::get('/tasks/{id}/downloadAttachments', 'TaskController@downloadAttachments')->name('api.downloadAttachemnts');

    Route::get('/requests/user', 'RequestController@index')->name('requests.index');
    Route::get('/requests/head', 'RequestController@index')->name('requests.index');
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

    Route::get('/responsibilities/{id}/moveup', 'ResponsibilityController@moveup')->name('api.responsibilities.moveup');
    Route::get('/responsibilities/{id}/movedown', 'ResponsibilityController@movedown')->name('api.responsibilities.movedown');
    Route::post('/responsibility_descriptions/moveup', 'ResponsibilityDescriptionController@moveup')->name('api.responsibilityDescription.moveup');
    Route::post('/responsibility_descriptions/movedown', 'ResponsibilityDescriptionController@movedown')->name('api.responsibilityDescription.movedown');

    Route::get('/tasks/filter', 'TaskController@filter')->name('api.tasks.filter');
    Route::get('/tasks/paginate', 'TaskController@paginate')->name('api.tasks.paginate');
    Route::put('/tasks/{id}/responsibilitydescription', 'TaskController@responsibilitydescription')->name('api.tasks.responsibilitydescription');
    Route::put('/tasks/{id}/description', 'TaskController@description')->name('api.tasks.description');
    Route::put('/tasks/{id}/deadline', 'TaskController@deadline')->name('api.tasks.deadline');
    Route::put('/tasks/{id}/planned_time', 'TaskController@planned_time')->name('api.tasks.planned_time');
    Route::put('/tasks/{id}/author', 'TaskController@author')->name('api.tasks.author');
    Route::put('/tasks/{id}/watchers', 'TaskController@watchers')->name('api.tasks.watchers');
    Route::delete('/tasks/{taskId}/tags/{tagId}', 'TaskController@detachTag')->name('api.tasks.detachTag');
    Route::put('/tasks/{id}/tags', 'TaskController@attachTag')->name('api.tasks.attachTag');
    Route::post('/tasks', 'TaskController@store')->name('tasks.store');
    Route::put('/tasks/{id}/priority', 'TaskController@updatePriority')->name('api.tasks.updatePriority');
    Route::post('/tasks/checkStartDate', 'TaskController@checkStartDate')->name('api.tasks.checkStartDate');

    Route::get('/users', 'UserController@getUsers')->name('api.getUsers');
    Route::get('/tasks/groupBy/{field}', 'TaskController@group')->name('api.tasks.group');
    Route::post('/users/{id}/tasks', 'ProfileController@getTasks')->name('api.profile.getTasks');
    Route::post('/users/{id}/timesets', 'ProfileController@getTimeSets')->name('api.profile.getTimeSets');

    Route::get('/divisions/users', 'DivisionController@getUsers')->name('api.divisions.getUsers');
    Route::get('/divisions', 'DivisionController@getDivisions')->name('api.divisions.getDivisions');

    Route::get('/tasks/tags', 'TaskController@tags')->name('api.tasks.tags');

    Route::get('/tasks/{id}/start', 'TaskController@start')->name('api.tasks.start');
    Route::get('/tasks/{id}/pause', 'TaskController@pause')->name('api.tasks.pause');
    Route::get('/tasks/{id}/stop', 'TaskController@stop')->name('api.tasks.stop');
    Route::get('/users/tasks', 'TaskController@usersTasks')->name('api.users.tasks');
    Route::get('/divisions/{id}/tasks', 'TaskController@divisionTasks')->name('api.divisions.tasks');

    Route::post('/attachments', 'AttachmentController@create')->name('api.attachmetns.create');
    Route::delete('/attachments/{id}', 'AttachmentController@destroy')->name('api.attachments.destroy');

    Route::post('/notifications', 'UserController@loadNotifications')->name('api.notificatinos.load');
    Route::get('/timesets', 'TimesetController@getTimesets')->name('timesets.getTimesets');

    Route::get('/userResponsibleTasks', 'TaskController@userResponsibleTasks')->name('user.responsible.tasks');

    Route::post('/createCalendarEvent', 'TaskController@createCalendarEvent')->name('create.calendar.event');
    Route::get('/tasksForCalendarEvents', 'TaskController@tasksForCalendarEvents')->name('tasks.calendar.events');
    Route::post('/deleteCalendarEvent', 'TaskController@deleteCalendarEvent')->name('delete.calendar.event');

    Route::post('/users/edit', 'UserController@edit')->name('user.edit');

    Route::post('/repeatition/{id}', 'RepititionController@edit')->name('repeatition.edit');
    Route::post('/repeatition', 'RepititionController@create')->name('repeatition.create');
    Route::post('/repeatition/{id}/delete', 'RepititionController@delete')->name('repeatition.delete');

    
    Route::put('/entries/{id}', 'EntryController@update')->name('entries.update');

    Route::post('/facilities', 'FacilityController@create')->name('facilities.create');
    Route::post('/facilities/{id}', 'FacilityController@edit')->name('facilities.edit');
    Route::delete('/facilities/{id}', 'FacilityController@delete')->name('facilities.delete');
    Route::get('/entries', 'EntryController@getEntries')->name('entries.getEntries');

    Route::get('/getRequests', 'RequestController@getRequests')->name('requests.getRequests');
    Route::post('/requests', 'RequestController@store')->name('requests.create');
    Route::put('/requests/{id}', 'RequestController@store')->name('requests.store');
    Route::delete('/requests/{id}', 'RequestController@delete')->name('requests.delete');
    Route::post('/requests/{id}/changeStatus', 'RequestController@changeStatus')->name('requests.changeStatus');
    Route::get('/requests/head/users', 'RequestController@headUsers')->name('head.users');
});
