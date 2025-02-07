<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class ProjectTasksController extends Controller
{
    public function store(Project $project)
    {
        $this->authorize('update', $project);

        request()->validate([
            'body' => ['required']
        ]);

        $project->addTask(request('body'));

        return redirect()->to($project->path());
    }

    public function update(Project $project, Task $task)
    {

        $this->authorize('update', $project);

        request()->validate([
            'body' => ['required']
        ]);
        
        $task->update([
            'body' => request('body'),
        ]);

        $method = request('completed') ? 'complete' : 'incomplete';

        $task->$method();

        return redirect()->to($project->path());
    }
}
