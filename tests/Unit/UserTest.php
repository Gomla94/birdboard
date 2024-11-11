<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_user_has_projects()
    {
        $user = User::factory()->make();
        $this->assertInstanceOf(Collection::class, $user->projects);
    }
}
