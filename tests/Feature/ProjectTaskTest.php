<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectTaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_add_tasks()
    {
        $attributes = Project::factory()->raw();

        $project = Project::factory()->create($attributes);

        $this->post($project->path().'/'.'tasks')->assertRedirect('login');
    }

    public function test_project_can_have_tasks()
    {        
        $project = ProjectFactory::ownedBy($this->authenticate())->create();

        $this->post($project->path().'/'.'tasks', [
            'body' => 'test task'
        ]);

        $this->get($project->path())->assertSee('test task');
    }

    public function test_task_requires_a_body()
    {        
        $this->authenticate();

        $attributes = Task::factory()->raw([
            'body' => ''
        ]);
        
        $project = auth()->user()->projects()->create(
            Project::factory()->raw()
        );

        $this->post($project->path().'/'.'tasks', $attributes)
        ->assertSessionHasErrors('body');
    }

    public function test_only_project_owner_can_add_tasks()
    {
        $this->authenticate();
        
        $attributes = Project::factory()->raw();

        $project = Project::factory()->create($attributes);
        
        $this->post($project->path().'/'.'tasks')->assertStatus(403);
    }

    public function test_task_can_be_updated()
    {                
        $project = ProjectFactory::ownedBy($this->authenticate())->withTasks(1)->create();

        $this->patch($project->path().'/tasks/'.$project->tasks[0]->id, [
            'body' => 'new body',
            'completed' => true
        ])->assertRedirect($project->path());

        $this->assertDatabaseHas('tasks', [
            'body' => 'new body',
            'completed' => true
        ]);
    }

    public function test_project_owner_can_update_a_task()
    {
        $this->authenticate();
   
        $project = ProjectFactory::withTasks(1)->create();

        $this->patch($project->path().'/tasks/'.$project->tasks->first()->id, [
            'body' => 'new body',
            'completed' => true
        ])->assertStatus(403);

        $this->assertDatabaseMissing('tasks', [
            'body' => 'new body',
            'completed' => true
        ]);
    }

}
