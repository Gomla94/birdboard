<?php

namespace App\Http\Controllers;

use App\Http\Requests\projects\StoreProjectRequest;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectsController extends Controller
{
    public function index()
    {

        $projects = auth()->user()->projects()->select('id', 'title', 'description')->get();

        return view('projects.index', compact('projects'));
    }

    public function show(Project $project)
    {
        $this->authorize('update', $project);

        return view('projects.show', compact('project'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store()
    {       

        $project = auth()->user()->projects()->create($this->validateRequests());  

        return redirect($project->path());
    }

    public function edit(Project $project)
    {
        return view("projects.edit", compact('project'));
    }

    public function update(StoreProjectRequest $request)
    {
        $project = $request->persist();

        return redirect()->to($project->path());
    }

    protected function validateRequests()
    {
        return request()->validate([
            'title' => ['sometimes', 'string'],
            'description' => ['sometimes', 'string'],
            'notes' => ['string', 'nullable']
        ]);
    }
}
