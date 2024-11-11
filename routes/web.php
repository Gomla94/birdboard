<?php

use App\helpers\ExternalApiHelper;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\ProjectTasksController;
use App\Models\Activity;
use App\Models\Project;
use Illuminate\Support\Facades\Route;

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

// \App\Models\Project::created(function(Project $project) {
//     Activity::create([
//         'description' => 'created',
//         'project_id' => $project->id
//     ]);
// });

// \App\Models\Project::updated(function(Project $project) {
//     Activity::create([
//         'description' => 'updated',
//         'project_id' => $project->id
//     ]);
// });

Route::get('/', function () {
    // return ExternalApiHelper::bar();
    // $external = app(ExternalApiHelper::class);
    // the app method is a short call to application service provider.
    // $externalApi = ExternalApiHelper::setFoo('hello world!');
    // $externalApi2 = ExternalApiHelper::setFoo('hello world again!');
    // here each variable has its own externalApiHelper object and storing
    // and referencing its own foo value.(with bind)
    // but with singleton we are runing setFoo method on the same object
    // just stored in 2 different variables.
    // using singleton ensures that only one object of the class is initialized at a time
    // throughout the execution of the application
    // return $externalApi->foo().' - '.$externalApi2->foo().' - '.$external->foo();
});

Route::group(['middleware' => 'auth'], function() {
    Route::get('/projects', [ProjectsController::class, 'index']);
    Route::get('/projects/create', [ProjectsController::class, 'create']);
    Route::get('/projects/{project}', [ProjectsController::class, 'show']);
    Route::get('/projects/{project}/edit', [ProjectsController::class, 'edit']);
    Route::patch('/projects/{project}', [ProjectsController::class, 'update']);
    Route::post('/projects', [ProjectsController::class, 'store']);

    Route::post('/projects/{project}/tasks', [ProjectTasksController::class, 'store']);
    Route::patch('/projects/{project}/tasks/{task}', [ProjectTasksController::class, 'update']);

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});

Auth::routes();


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
