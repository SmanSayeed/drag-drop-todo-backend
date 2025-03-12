<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user registration
     */
    public function test_user_can_register(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertStatus(201)
                 ->assertJsonPath('success', true)
                 ->assertJsonPath('message', 'User registered successfully')
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         'user' => [
                             'id',
                             'name',
                             'email',
                             'created_at',
                             'updated_at',
                         ],
                         'access_token',
                         'token_type',
                     ],
                 ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
    }

    /**
     * Test user login
     */
    public function test_user_can_login(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('Password123!'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'Password123!',
        ]);

        $response->assertStatus(200)
                 ->assertJsonPath('success', true)
                 ->assertJsonPath('message', 'User logged in successfully')
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         'user' => [
                             'id',
                             'name',
                             'email',
                             'created_at',
                             'updated_at',
                         ],
                         'access_token',
                         'token_type',
                     ],
                 ]);
    }

    /**
     * Test invalid login
     */
    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('Password123!'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
                 ->assertJsonPath('success', false)
                 ->assertJsonPath('message', 'Invalid login credentials');
    }

    /**
     * Test protected route access
     */
   /**
     * Test protected route access
     */
    public function test_user_can_access_protected_route_with_token(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/user');

        // Debug the response to see what we're getting
        \Log::info('API user route response: ' . $response->getContent());

        $response->assertStatus(200)
                 ->assertJson(['success' => true])
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'id',
                         'name',
                         'email',
                         'created_at',
                         'updated_at',
                     ],
                 ]);
    }

    /**
     * Test protected route access without token
     */
    public function test_user_cannot_access_protected_route_without_token(): void
    {
        $response = $this->getJson('/api/user');

        $response->assertStatus(401)
                 ->assertJsonPath('success', false)
                 ->assertJsonPath('message', 'Unauthorized');
    }

    /**
     * Test user logout
     */
    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/logout');

        $response->assertStatus(200)
                 ->assertJsonPath('success', true)
                 ->assertJsonPath('message', 'User logged out successfully');

        // Verify token has been deleted
        $this->assertDatabaseCount('personal_access_tokens', 0);
    }
}