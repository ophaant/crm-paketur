<?php

use Illuminate\Foundation\Testing\RefreshDatabase;


it('throw 404 in root url', function () {
    $response = $this->get('/');

    $response->assertStatus(404);
});

it('can access base url api', function () {
    $response = $this->get('/api');

    $response->assertStatus(200)
    ->assertJson([
        'rc' => '0200',
        'success' => true,
        'message' => 'Welcome to the CRM API'
    ]);
});
