<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;

class ProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;
   
    // RefreshDatabase will clear the database, run any migrate that is necessary


    public function test_redirect_to_login_if_user_not_logged_in()
    {        
        $attributes = Project::factory()->raw();

        $this->post('/projects', $attributes)->assertRedirect('login');
    }

    public function test_guests_cannot_view_projects()
    {        
        $this->get('/projects')->assertRedirect('login');
        $project = Project::factory()->create();
        $this->get($project->path())->assertRedirect('login');
    }


    public function test_a_user_can_create_project()
    {
        $this->withoutExceptionHandling();

        $this->authenticate();

        $this->get('/projects/create')->assertStatus(200);
        
        $attributes = [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->sentence(),
            'notes' => $this->faker->paragraph()
        ];

        $response = $this->post('/projects', $attributes);

        $project = Project::where($attributes)->first();

        $response->assertRedirect($project->path());
        
        $this->assertDatabaseHas('projects', $attributes);

        $this->get($project->path())->assertSee($attributes['title'])
        ->assertSee($attributes['description'])
        ->assertSee($attributes['notes']);
    }

    public function test_project_requires_title()
    {
        // create will build up the attributes and save it to the database.
        // make will build up the attributes.
        // raw will build up the attributes and store it as array not object.

        $this->authenticate();

        $attributes = Project::factory()->raw([
            'title' => ''
        ]);
        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    public function test_project_requires_description()
    {
        $this->authenticate();

        $attributes = Project::factory()->raw([
            'description' => ''
        ]);

        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }

    public function test_user_can_view_projects()
    {
                
        $project = ProjectFactory::ownedBy($this->authenticate())->create();

        $this->get('/projects')
        ->assertSee($project->title);
    }

    public function test_user_can_view_own_project()
    {
        $project = ProjectFactory::ownedBy($this->authenticate())->create();

        $this->get($project->path())
        ->assertSee($project->title)
        ->assertSee($project->description);
    }

    public function test_authenticated_user_cannot_view_others_projects()
    {
        $this->authenticate();

        $project = Project::factory()->create();

        $this->get($project->path())->assertStatus(403);

    }

    public function test_a_user_can_update_project()
    {
        $this->withoutExceptionHandling();

        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
        ->patch($project->path(), $attributes = ['title' => 'new notes',
        'description' => 'new description'])
        ->assertRedirect($project->path());

        $this->assertDatabaseHas('projects', [
            'title' => $attributes['title'],
            'description' => $attributes['description']
        ]);

    }

    public function test_user_can_update_notes()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
        ->patch($project->path(), $attributes = ['notes' => 'new notes'])
        ->assertRedirect($project->path());

        $this->assertDatabaseHas('projects', [
            'notes' => $attributes['notes'],
        ]);
    }

    public function test_authenticated_user_cannot_update_others_projects()
    {
        $this->authenticate();

        $project = Project::factory()->create();

        $this->patch($project->path())->assertStatus(403);

    }
}
