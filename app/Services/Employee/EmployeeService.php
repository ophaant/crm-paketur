<?php

namespace App\Services\Employee;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use LaravelEasyRepository\BaseService;

interface EmployeeService extends BaseService{

    public function list(Request $request): array;

    public function create(mixed $data) :array;

    public function show(int $id) :array;

    public function update(mixed $id, mixed $data) :array;

    public function delete(mixed $id) :void;
}
