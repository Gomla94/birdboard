@extends('layouts.app')
@section('content')
<form action="/projects" method="POST" class="lg:w-1/2 lg:mx-auto bg-white shadow p-6 rounded md:py-12 md:px-12">
    @csrf

    <h3 class="text-2xl font-normal mb-10 text-center">Let's start something new</h3>
    @include('projects.form', [
        'project' => new \App\Models\Project,
        'buttonText' => 'Create project'
    ])
</form>
@endsection