<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\Task;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_belongs_to_project()
    {
        $this->authenticate();
        
        $task = Task::factory()->create();

        $this->assertInstanceOf(Project::class, $task->project);
    }


    public function test_it_has_a_path()
    {
        $this->authenticate();
        
        $project = Project::factory()->create();

        $task = $project->addTask('new task');

        $this->assertEquals($project->path().'/tasks/'.$task->id, $task->path());
    }

   public function test_task_can_be_completed()
   {
        $task = Task::factory()->create();

        // $this->assertFalse($task->fresh()->completed); this line will work because fresh() returns all the default values.
        
        $this->assertFalse($task->completed);
        // with this approach we need to specify the completed column value in the task factory.

        $task->complete();

        $this->assertTrue($task->fresh()->completed);
   }

   public function test_task_can_be_marked_incompleted()
   {
        $this->withoutExceptionHandling();
        $task = Task::factory()->create(['completed' => true]);

        // $this->assertFalse($task->fresh()->completed); this line will work because fresh() returns all the default values.
        
        $this->assertTrue($task->completed);
        // with this approach we need to specify the completed column value in the task factory.


        $this->actingAs($task->project->owner)->patch($task->path(), ['body' => 'chnaged', 'completed' => false]);
        $this->assertFalse($task->fresh()->completed);
        // $task->complete();

        // $this->assertTrue($task->fresh()->completed);
   }
}
