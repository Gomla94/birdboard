@extends('layouts.app')
@section('content')
<form action="{{$project->path()}}" method="POST" class="lg:w-1/2 lg:mx-auto bg-white shadow p-6 rounded md:py-12 md:px-12">
    @csrf
    @method('PATCH')
    
    <h3 class="text-2xl font-normal mb-10 text-center">Edit project: {{ $project->title }}</h3>
    @include('projects.form', [
        'buttonText' => 'Update project'
    ])
</form>
@endsection