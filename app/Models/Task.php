<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $touches = ['project'];
    protected $casts = ['completed' => 'boolean'];


    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function path()
    {
        return "/projects/{$this->project->id}/tasks/{$this->id}";
    }

    public static function boot()
    {
        parent::boot();

        static::created(function($task) {
            $task->recordActivity('task_created');
        });

        static::deleted(function($task) {
            $task->recordActivity('task_deleted');
        });
    }


    public function complete()
    {
        $this->update([
            'completed' => true
        ]);

        $this->recordActivity('task_completed');
    }

    public function incomplete()
    {
        $this->update([
            'completed' => false
        ]);

        $this->recordActivity('task_incompleted');
    }

    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject')->latest();
    }

    public function recordActivity($description)
    {
        $this->activity()->create([
            'project_id' => $this->project_id,
            'description' => $description
        ]);
    }
}
