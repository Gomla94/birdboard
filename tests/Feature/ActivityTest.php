<?php

namespace Tests\Feature;

use App\Models\Task;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_project_creat_activity()
    {
        $project = ProjectFactory::create();

        $this->assertCount(1, $project->activity);
        $this->assertDatabaseHas('activities', [
            'project_id' => $project->id,
            'description' => 'created'
        ]);
    }

    public function test_updating_project_create_activity()
    {
        $project = ProjectFactory::create();

        $oldTitle = $project->title;

        $project->update([
            'title' => 'changed'
        ]);

        $this->assertCount(2, $project->activity);
        // $this->assertDatabaseHas('activities', [
        //     'project_id' => $project->id,
        //     'description' => 'updated'
        // ]);

        tap($project->activity->last(), function($activity) use($oldTitle, $project) {
            $this->assertEquals('updated', $activity->description);

            $expectedChanges = [
                'before' => [
                    'title' => $oldTitle,
                ],
                'after' => [
                    'title' => 'changed',
                ]
            ];

            $this->assertEquals($expectedChanges, $activity->changes);
        });
    }

    public function test_creating_task_adding_activity()
    {
        $project = ProjectFactory::create();

        $task = $project->addTask('new task');

        $this->assertcount(2, $project->activity);

        tap($project->activity->last(), function($activity) {
            $this->assertInstanceOf(Task::class, $activity->subject);
            $this->assertEquals('new task', $activity->subject->body);
        });
    }

    public function test_completing_task_adding_activity()
    {
        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)->patch($project->tasks[0]->path(), [
            'body' => 'changed',
            'completed' => true
        ]);

        $this->assertcount(3, $project->activity);

        tap($project->activity->last(), function($activity) {
            $this->assertInstanceOf(Task::class, $activity->subject);
            $this->assertEquals('task_completed', $activity->description);
        });
    }

    public function test_incompleting_task_adding_activity()
    {
        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)->patch($project->tasks[0]->path(), [
            'body' => 'changed',
            'completed' => true
        ]);

        $this->assertCount(3, $project->activity);

        $this->actingAs($project->owner)->patch($project->tasks[0]->path(), [
            'body' => 'changed',
            'completed' => false
        ]);

        // $this->assertCount(4, $project->activity); this line will return the already loaded project activities count
        // which is 3, so we need to use fresh method on the $project instance gto get the latest count.
        $this->assertCount(4, $project->fresh()->activity);

        tap($project->fresh()->activity->last(), function($activity) {
            $this->assertInstanceOf(Task::class, $activity->subject);
            $this->assertEquals('task_incompleted', $activity->description);
        });
        // $this->assertEquals('task_incompleted', $project->fresh()->activity->last()->description);
    }

    public function test_deleting_task_adding_activity()
    {
        $project = ProjectFactory::create();

        $task = $project->addTask('new task');

        $project->tasks->last()->delete();

        $this->assertcount(3, $project->activity);
        $this->assertEquals('task_deleted', $task->project->activity->last()->description);
    }
}
