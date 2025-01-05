<?php

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->artisan('migrate:fresh');
    $this->seed(DatabaseSeeder::class);
});

test('manager can only see managers from their company', function () {

    $manager1 = User::role('manager')->where('email', 'manager1@example.com')->first();

    $token = JWTAuth::fromUser($manager1);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->getJson('/api/managers');

    $response->assertStatus(200);

    $managers = $response->json('data');
    expect($managers)->not()->toBeEmpty();
});

test('manager cannot see managers from other company', function () {

    $manager1 = User::role('manager')->where('company_id', 1)->first();
    $manager2 = User::role('manager')->where('company_id', 2)->first();

    $token = JWTAuth::fromUser($manager1);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->getJson('/api/managers?search=' . $manager2->name);

    $response->assertStatus(404)
        ->assertJson([
            'rc' => '0404',
            'message' => 'Record not found'
        ]);
});

test('manager can see detail manager from their company', function () {

    $manager1 = User::role('manager')->whereEmail('manager1@example.com')->where('company_id', 1)->first();
    $manager2 = User::role('manager')->whereEmail('manager2@example.com')->where('company_id', 1)->first();

    $token = JWTAuth::fromUser($manager1);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->getJson('/api/managers/' . $manager2->id);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'rc',
            'message',
            'data' => []
        ]);
});

test('manager cannot see detail manager from other company', function () {

    $manager1 = User::role('manager')->whereEmail('manager1@example.com')->where('company_id', 1)->first();
    $manager2 = User::role('manager')->where('company_id', 2)->first();

    $token = JWTAuth::fromUser($manager1);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->getJson('/api/managers/' . $manager2->id);

    $response->assertStatus(404)
        ->assertJson([
            'rc' => '0404',
            'message' => 'Record not found'
        ]);
});

test('manager can create manager', function () {

    $manager1 = User::role('manager')->where('company_id', 1)->first();

    $token = JWTAuth::fromUser($manager1);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->postJson('/api/managers/', [
            'name' => 'Manager Test',
            'email' => 'manager_test@gmail.com',
            'phone' => '081234567890',
            'address' => 'Jl. Test No. 1',
            'password' => 'P455word@',
            'password_confirmation' => 'P455word@'
        ]);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'rc',
            'message',
            'data' => []
        ]);
});

test('manager can update manager', function () {

    $manager1 = User::role('manager')->where('company_id', 1)->first();
    $manager2 = User::role('manager')->where('company_id', 1)->where('email', 'manager4@example.com')->first();

    $token = JWTAuth::fromUser($manager1);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->putJson('/api/managers/'.$manager2->id, [
            'name' => 'Manager 4 Test',
            'email' => 'manager4_test@gmail.com',
            'phone' => '081234567890',
            'address' => 'Jl. Test No. 1',
            'password' => 'P455word@',
            'password_confirmation' => 'P455word@'
        ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'rc',
            'message',
            'data' => []
        ]);
});

test('manager can delete manager', function () {

    $manager1 = User::role('manager')->where('company_id', 1)->first();
    $manager2 = User::role('manager')->where('company_id', 1)->where('email', 'manager4@example.com')->first();

    $token = JWTAuth::fromUser($manager1);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->deleteJson('/api/managers/'.$manager2->id);

    $response->assertStatus(200)
        ->assertJson([
            'rc' => '0204',
            'message' => 'Delete successfully',
            'data' => []
        ]);
});

test('manager can see list of employees from their company', function () {

    $manager1 = User::role('manager')->where('company_id', 1)->first();

    $token = JWTAuth::fromUser($manager1);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->getJson('/api/employees');

    $response->assertStatus(200);

    $employees = $response->json('data');
    expect($employees)->not()->toBeEmpty();
});

test('manager can see detail employee from their company', function () {

    $manager1 = User::role('manager')->where('company_id', 1)->first();
    $employee1 = User::role('employee')->where('company_id', 1)->first();

    $token = JWTAuth::fromUser($manager1);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->getJson('/api/employees/' . $employee1->id);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'rc',
            'message',
            'data' => []
        ]);
});

test('manager can create employee', function () {

    $manager1 = User::role('manager')->where('company_id', 1)->first();

    $token = JWTAuth::fromUser($manager1);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->postJson('/api/employees/', [
            'name' => 'Employee Test',
            'email' => 'employee_test@gmail.com',
            'phone' => '081234567890',
            'address' => 'Jl. Test No. 1',
            'password' => 'P455word@',
            'password_confirmation' => 'P455word@'
        ]);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'rc',
            'message',
            'data' => []
        ]);

});

test('manager can update employee', function () {

    $manager1 = User::role('manager')->where('company_id', 1)->first();
    $employee1 = User::role('employee')->where('company_id', 1)->first();

    $token = JWTAuth::fromUser($manager1);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->putJson('/api/employees/'.$employee1->id, [
            'name' => 'Employee Test',
            'email' => 'employee_test@gmail.com',
            'phone' => '081234567890',
            'address' => 'Jl. Test No. 1',
            'password' => 'P455word@',
            'password_confirmation' => 'P455word@'
        ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'rc',
            'message',
            'data' => []
        ]);
});

test('manager can delete employee', function () {

    $manager1 = User::role('manager')->where('company_id', 1)->first();
    $employee1 = User::role('employee')->where('company_id', 1)->first();

    $token = JWTAuth::fromUser($manager1);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->deleteJson('/api/employees/'.$employee1->id);

    $response->assertStatus(200)
        ->assertJson([
            'rc' => '0204',
            'message' => 'Delete successfully',
            'data' => []
        ]);
});

test('manager cannot input wrong format when create manager', function () {

    $manager1 = User::role('manager')->where('company_id', 1)->first();

    $token = JWTAuth::fromUser($manager1);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->postJson('/api/managers/', [
            'name' => 'Manager Test Panjang Dulu Ya Biar Error',
            'email' => 'manager*&@example.com',
            'phone' => '08123456789012345',
            'address' => 'Jl. Test No. 1',
            'password' => 'P455word',
            'password_confirmation' => 'P455word@'
        ]);

    $response->assertStatus(400)
        ->assertJsonStructure([
            'rc',
            'error',
            'message',
        ]);
});

test('manager cannot input wrong format when update manager', function () {

    $manager1 = User::role('manager')->where('company_id', 1)->first();
    $manager2 = User::role('manager')->where('company_id', 1)->where('email', 'manager4@example.com')->first();

    $token = JWTAuth::fromUser($manager1);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->putJson('/api/managers/'.$manager2->id, [
            'name' => 'Manager Test Panjang Dulu Ya Biar Error',
            'email' => 'manager*&@example.com',
            'phone' => '08123456789012345',
            'address' => 'Jl. Test No. 1',
            'password' => 'P455word',
            'password_confirmation' => 'P455word@'
        ]);

    $response->assertStatus(400)
        ->assertJsonStructure([
            'rc',
            'error',
            'message',
        ]);
});

test('manager cannot input wrong format when create employee', function () {

    $manager1 = User::role('manager')->where('company_id', 1)->first();

    $token = JWTAuth::fromUser($manager1);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->postJson('/api/employees/', [
            'name' => 'Employee Test Panjang Dulu Ya Biar Error',
            'email' => 'manager*&@example.com',
            'phone' => '08123456789012345',
            'address' => 'Jl. Test No. 1',
            'password' => 'P455word',
            'password_confirmation' => 'P455word@'
        ]);

    $response->assertStatus(400)
        ->assertJsonStructure([
            'rc',
            'error',
            'message',
        ]);
});

test('manager cannot input wrong format when update employee', function () {

    $manager1 = User::role('manager')->where('company_id', 1)->first();
    $employee1 = User::role('employee')->where('company_id', 1)->first();

    $token = JWTAuth::fromUser($manager1);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->putJson('/api/employees/'.$employee1->id, [
            'name' => 'Employee Test Panjang Dulu Ya Biar Error',
            'email' => 'manager*&@example.com',
            'phone' => '08123456789012345',
            'address' => 'Jl. Test No. 1',
            'password' => 'P455word',
            'password_confirmation' => 'P455word@'
        ]);

    $response->assertStatus(400)
        ->assertJsonStructure([
            'rc',
            'error',
            'message',
        ]);
});

test('manager cannot see employee from other company', function () {

    $manager1 = User::role('manager')->where('company_id', 1)->first();
    $employee2 = User::role('employee')->where('company_id', 2)->first();

    $token = JWTAuth::fromUser($manager1);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->getJson('/api/employees?search=' . $employee2->name);

    $response->assertStatus(404)
        ->assertJson([
            'rc' => '0404',
            'message' => 'Record not found'
        ]);
});

test('manager cannot see detail employee from other company', function () {

    $manager1 = User::role('manager')->where('company_id', 1)->first();
    $employee2 = User::role('employee')->where('company_id', 2)->first();

    $token = JWTAuth::fromUser($manager1);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->getJson('/api/employees/' . $employee2->id);

    $response->assertStatus(404)
        ->assertJson([
            'rc' => '0404',
            'message' => 'Record not found'
        ]);
});

test('manager cannot access company', function () {

    $manager1 = User::role('manager')->where('company_id', 1)->first();

    $token = JWTAuth::fromUser($manager1);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->getJson('/api/companies');

    $response->assertStatus(403)
        ->assertJson([
            'rc' => '0403',
            'message' => 'You are not authorized to access this resource'
        ]);
});
