<div class="card" style="min-height: 300px">
    <h3 class="mb-4 py-4 -ml-5 pl-4 border-l-4 border-sky-400">
        <a href="{{ $project->path() }}" class="text-black no-underline">
            {{ $project->title }}
        </a>
    </h3>
    <div class="text-gray-500">
        {{ str_limit($project->description, 200) }}
    </div>
</div>