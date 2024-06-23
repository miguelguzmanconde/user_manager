<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase {
    use RefreshDatabase;

    public function test_if_admin_redirect_works_as_guest(): void {

        $user = User::factory()->create([
            'type' => 'guest',
        ]);
        $response = $this
            ->actingAs($user)
            ->get('/user/list');
        $response
            ->assertRedirect('/profile');

    }

    public function test_if_admin_redirect_works_as_admin(): void {

        $user = User::factory()->create([
            'type' => 'admin',
        ]);
        $response = $this
            ->actingAs($user)
            ->get('/user/list');
        $response
            ->assertStatus(200);

    }

}
