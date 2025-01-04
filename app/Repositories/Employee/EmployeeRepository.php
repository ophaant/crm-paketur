<?php

namespace App\Repositories\Employee;

use Illuminate\Http\Request;
use LaravelEasyRepository\Repository;

interface EmployeeRepository extends Repository{

    public function list(Request $request) : array;
}
