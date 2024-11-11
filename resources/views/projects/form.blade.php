<div class="form-group mb-6">
    <label for="title" class="text-sm mb-2 block">Title</label>
    <div class="control">
        <input type="text" name="title" placeholder="My next awesome project" value="{{ $project->title }}" style="border:1px solid gray;border-radius:5px;padding:5px" class="form-control w-full input rounded p-2 border-4 border-sky-500">
    </div>
</div>
<div class="form-group">
    <label for="description" class="text-sm mb-2 block">Description</label>
    <div>
        <textarea name="description" cols="30" rows="10" style="border:1px solid gray;border-radius:5px;padding:5px" placeholder="i should start learning piano" class="form-control p-2 w-full rounded border-gray-300">{{ $project->description }}</textarea>
    </div>
</div>
<button class="btn btn-primary btn-sm button">{{$buttonText}}</button>
<a href="/projects">Cancel</a>

@if($errors)
<ul class="mt-2">
@foreach($errors->all() as $error)
    <li class="text-red-500">{{ $error }}</li>
@endforeach
</ul>
@endif