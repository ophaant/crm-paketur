<?php

namespace App\Services\Company;

use Illuminate\Http\Request;
use LaravelEasyRepository\BaseService;

interface CompanyService extends BaseService{

    public function list(Request $request): array;

    public function create(mixed $data) :array;

    public function show(int $id) :array;

    public function update(mixed $id, mixed $data) :array;

    public function delete(mixed $id) :void;
}
