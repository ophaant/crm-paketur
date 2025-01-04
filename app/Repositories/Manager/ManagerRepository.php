<?php

namespace App\Repositories\Manager;

use Illuminate\Http\Request;
use LaravelEasyRepository\Repository;

interface ManagerRepository extends Repository{

    public function list(Request $request) : array;
}
