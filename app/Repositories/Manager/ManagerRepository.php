<?php

namespace App\Repositories\Manager;

use App\Models\User;
use Illuminate\Http\Request;
use LaravelEasyRepository\Repository;

interface ManagerRepository extends Repository{

    public function list(Request $request) : array;

    public function findManagerById(int $id): User;
}
