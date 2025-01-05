<?php

use App\Models\Company;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->artisan('migrate:fresh');
    $this->seed(DatabaseSeeder::class);
});

test('super admin can list all company', function () {

    $admin = User::role('super_admin')->first();

    $token = JWTAuth::fromUser($admin);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->getJson('/api/companies');

    $response->assertStatus(200);

    $companies = $response->json('data');
    expect($companies)->not()->toBeEmpty();
});

// company can see detail company
test('super admin can see detail company', function () {

    $admin = User::role('super_admin')->first();
    $company = Company::first();

    $token = JWTAuth::fromUser($admin);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->getJson('/api/companies/' . $company->id);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'rc',
            'message',
            'data' => []
        ]);
});

// company can create company
test('super admin can create company', function () {

    $admin = User::role('super_admin')->first();

    $token = JWTAuth::fromUser($admin);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->postJson('/api/companies', [
            'name' => 'Company Test',
            'email' => 'company@gmail.com',
            'phone' => '08123456789',
        ]);

    $response->assertStatus(201)
        ->assertJsonStructure(
            [
                'rc',
                'message',
                'data' => []
            ]
        );
});

// super admin can update company
test('super admin can update company', function () {

    $admin = User::role('super_admin')->first();
    $company = Company::first();

    $token = JWTAuth::fromUser($admin);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->putJson('/api/companies/' . $company->id, [
            'name' => 'Company Test',
            'email' => 'company_baru@gmail.com',
            'phone' => '08123456789',
        ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'rc',
            'message',
            'data' => []
        ]);
});

// super admin can delete company
test('super admin can delete company', function () {

    $admin = User::role('super_admin')->first();
    $company = Company::first();

    $token = JWTAuth::fromUser($admin);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->deleteJson('/api/companies/' . $company->id);

    $response->assertStatus(200)
        ->assertJson([
            'rc' => '0204',
            'message' => 'Delete successfully',
            'data' => []
        ]);
});

// super admin cannot access employee
test('super admin cannot access employee', function () {

    $admin = User::role('super_admin')->first();

    $token = JWTAuth::fromUser($admin);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->getJson('/api/employees');

    $response->assertStatus(403)
        ->assertJson([
            'rc' => '0403',
            'message' => 'You are not authorized to access this resource'
        ]);
});

// super admin cannot access manager
test('super admin cannot access manager', function () {

    $admin = User::role('super_admin')->first();

    $token = JWTAuth::fromUser($admin);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->getJson('/api/managers');

    $response->assertStatus(403)
        ->assertJson([
            'rc' => '0403',
            'message' => 'You are not authorized to access this resource'
        ]);
});
