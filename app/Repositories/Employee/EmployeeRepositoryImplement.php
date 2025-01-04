<?php

namespace App\Repositories\Employee;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use LaravelEasyRepository\Implementations\Eloquent;

class EmployeeRepositoryImplement extends Eloquent implements EmployeeRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property User|mixed $model;
    */
    protected User $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function list(Request $request): array
    {
         return $this->employeeBuilder($request)
            ->paginate($request->get('per_page', 10))
            ->withQueryString()
            ->toArray();
    }

    public function employeeBuilder(Request $request): Builder
    {
        return $this->model->role('employee')->where(function ($query) use ($request){
            $query->filterByIlikeName($request->get('search'));
        })->sortBy($request->get('sort_by'), $request->get('sort_order'));
    }
}
