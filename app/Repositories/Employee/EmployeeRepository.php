<?php

namespace App\Repositories\Employee;

use App\Models\User;
use Illuminate\Http\Request;
use LaravelEasyRepository\Repository;

interface EmployeeRepository extends Repository{

    public function list(Request $request) : array;

    public function findEmployeeById(int $id): User;
}
