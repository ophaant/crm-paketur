<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\DatabaseSeeder;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->artisan('migrate:fresh');
    $this->seed(DatabaseSeeder::class);
});

test('employee can only see employees from their company', function () {

    $employee1 = User::role('employee')->where('email', 'employee1@example.com')->first();

    $token = JWTAuth::fromUser($employee1);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->getJson('/api/employees');

    $response->assertStatus(200);

    $employees = $response->json('data');
    expect($employees)->not()->toBeEmpty();
});

test('employee cannot see employees from other company', function () {

    $employee1 = User::role('employee')->where('company_id', 1)->first();
    $employee2 = User::role('employee')->where('company_id', 2)->first();

    $token = JWTAuth::fromUser($employee1);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->getJson('/api/employees?search=' . $employee2->name);

    $response->assertStatus(404)
    ->assertJson([
        'rc' => '0404',
        'message' => 'Record not found'
    ]);
});

test('employee can see detail employee from their company', function () {

    $employee1 = User::role('employee')->whereEmail('employee1@example.com')->where('company_id', 1)->first();
    $employee2 = User::role('employee')->whereEmail('employee2@example.com')->where('company_id', 1)->first();

    $token = JWTAuth::fromUser($employee1);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->getJson('/api/employees/' . $employee2->id);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'rc',
            'message',
            'data' => []
        ]);
});

test('employee cannot see detail employee from other company', function () {

    $employee1 = User::role('employee')->whereEmail('employee1@example.com')->where('company_id', 1)->first();
    $employee2 = User::role('employee')->where('company_id', 2)->first();

    $token = JWTAuth::fromUser($employee1);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->getJson('/api/employees/' . $employee2->id);

    $response->assertStatus(404)
        ->assertJsonStructure([
            'rc',
            'message'
        ]);
});

test('employee cannot create employee', function () {

    $employee1 = User::role('employee')->whereEmail('employee1@example.com')->where('company_id', 1)->first();

    $token = JWTAuth::fromUser($employee1);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->postJson('/api/employees', [
            'name' => 'Employee Test',
            'email' => 'employee_test@gmail.com',
            'phone' => '081234567890',
            'address' => 'Jl. Test No. 1',
            'password' => 'P455word@',
            'password_confirmation' => 'P455word@'
        ]);

    $response->assertStatus(403)
        ->assertJson([
            'rc' => '0403',
            'message' => 'You are not authorized to access this resource'
        ]);
});

test('employee cannot update employee', function () {

    $employee1 = User::role('employee')->whereEmail('employee1@example.com')->where('company_id', 1)->first();
    $employee2 = User::role('employee')->whereEmail('employee4@example.com')->where('company_id', 1)->first();
    $token = JWTAuth::fromUser($employee1);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->putJson('/api/employees/'.$employee2->id, [
            'name' => 'Employee Test',
            'email' => 'employee_test@gmail.com',
            'phone' => '081234567890',
            'address' => 'Jl. Test No. 1',
            'password' => 'P455word@',
            'password_confirmation' => 'P455word@'
        ]);

    $response->assertStatus(403)
        ->assertJson([
            'rc' => '0403',
            'message' => 'You are not authorized to access this resource'
        ]);
});

test('employee cannot delete employee', function () {

    $employee1 = User::role('employee')->where('company_id', 1)->first();
    $employee2 = User::role('employee')->whereEmail('employee2@example.com')->where('company_id', 1)->first();

    $token = JWTAuth::fromUser($employee1);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->deleteJson('/api/employees/'.$employee2->id);

    $response->assertStatus(403)
        ->assertJson([
            'rc' => '0403',
            'message' => 'You are not authorized to access this resource'
        ]);
});

test('employee cannot access manager', function () {

    $employee1 = User::role('employee')->where('company_id', 1)->first();
    $manager = User::role('manager')->where('company_id', 1)->first();

    $token = JWTAuth::fromUser($employee1);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->getJson('/api/managers');

    $response->assertStatus(403)
        ->assertJson([
            'rc' => '0403',
            'message' => 'You are not authorized to access this resource'
        ]);
});

test('employee cannot access company', function () {

    $employee1 = User::role('employee')->where('company_id', 1)->first();

    $token = JWTAuth::fromUser($employee1);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->getJson('/api/companies');

    $response->assertStatus(403)
        ->assertJson([
            'rc' => '0403',
            'message' => 'You are not authorized to access this resource'
        ]);
});
