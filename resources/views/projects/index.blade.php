@extends('layouts.app')
@section('content')
    <div class="flex items-center justify-between mb-4 w-100">
        <h3 class="text-gray-500 font-normal">My Projects</h3>
        <a class="button" href="/projects/create">Create a project</a>
    </div>

    <div class="flex flex-wrap -mx-3">
        @forelse($projects as $project)
        <div class="w-1/3 h-34 px-3 pb-6 ">
            @include('projects.card')
        </div>                    
        @empty
            <p>No projects.</p>
        @endforelse
    </div>
        
@endsection