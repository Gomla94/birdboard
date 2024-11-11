@extends('layouts.app')
@section('content')
<header>
    <div class="flex justify-between items-center mb-6 w-100">
        <div>
            <p class="text-gray-500 font-normal">
                <a href="/projects" class="no-underline">My Projects</a> / 
                {{ $project->title }}
            </p>
        </div>
        <div>
            <a class="button" href="{{ $project->path().'/edit' }}">Edit project</a>
        </div>
    </div>
</header>
<main>
    <div class="lg:flex flex-row -mx-3">
        <div class="lg:w-3/4 px-3">
            <div class="mb-6">
                <h2 class="text-gray-400 font-normal">Tasks</h2>
                @foreach($project->tasks as $task)
                    <div class="card mb-3">
                        <form action="{{ $project->path() }}/tasks/{{ $task->id }}" method="POST">
                            @method('PATCH')
                            @csrf
                            <div class="flex">
                                <input type="text" name="body" class="w-full {{$task->completed ? 'text-gray-500' : ''}}" value="{{ $task->body }} ">                            
                                <input name="completed" type="checkbox" onchange="this.form.submit()" {{ $task->completed ? 'checked' : '' }}>
                            </div>       
                        </form>
                    </div>                
                @endforeach
                <div class="card">
                    <form action="{{ $project->path().'/tasks' }}" method="POST">
                        @csrf

                        <div class="flex">
                            <input class="w-full" name="body" type="text" placeholder="Add new task...">
                        </div>
                    </form>
                </div>
            </div>
            <div>
                <h2 class="text-gray-400 font-normal">General Notes</h2>
                
                <form action="{{ $project->path() }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <textarea name="notes" class="card w-full mb-3" style="min-height: 200px">{{ $project->notes }}</textarea>
                    <button class="button">Update</button>
                </form>
            </div>   
        </div>
            
        <div class="lg:w-1/4 px-3">
            @include('projects.card')
            @include('projects.activity.card')
        </div>
       
    </div>

</main>

@endsection