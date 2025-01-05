<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can login', function () {
    $user = User::factory()->create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password')
    ]);

    $response = $this->post('/api/login', [
        'email' => 'test@example.com',
        'password' => 'password'
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'rc',
            'message',
            'data' => []
        ]);
    $this->assertNotNull($response->json('data.access_token'));
});

test('can refresh token', function () {
    $token = $this->getJwtToken([
        'email' => 'test@example.com',
        'password' => 'password'
    ]);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->postJson('/api/refresh');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'rc',
            'message',
            'data' => []
        ]);

    $this->assertNotNull($response->json('data.access_token'));
    $this->assertNotSame($token, $response->json('data.access_token'));
});

test('can logout', function () {
    $token = $this->getJwtToken([
        'email' => 'test@example.com',
        'password' => 'password'
    ]);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->postJson('/api/logout');

    $response->assertStatus(200)
        ->assertJson([
            'rc'=> '0200',
            'message'=> 'Logout successfully',
            'data'=> []
        ]);

});

