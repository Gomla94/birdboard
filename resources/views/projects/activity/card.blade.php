<div class="card mt-2">
    <ul class="lis-small">
        @foreach($project->activity as $activity)
            <li class="mb-1">
                @include("projects.activity.{$activity->description}")
                <span class="text-gray-300">{{ $activity->created_at->diffForHumans(null, true) }}</span>
            </li>            
        @endforeach
    </ul>
</div>