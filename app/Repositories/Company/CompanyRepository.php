<?php

namespace App\Repositories\Company;

use Illuminate\Http\Request;
use LaravelEasyRepository\Repository;

interface CompanyRepository extends Repository{

    public function list(Request $request) : array;
}
