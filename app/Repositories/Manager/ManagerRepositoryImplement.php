<?php

namespace App\Repositories\Manager;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use LaravelEasyRepository\Implementations\Eloquent;

class ManagerRepositoryImplement extends Eloquent implements ManagerRepository{

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
        return $this->managerBuilder($request)
            ->paginate($request->get('per_page', 10))
            ->withQueryString()
            ->toArray();
    }

    public function managerBuilder(Request $request): Builder
    {
        return $this->model->role('manager')->where(function ($query) use ($request){
            $query->filterByIlikeName($request->get('search'));
        })->sortBy($request->get('sort_by'), $request->get('sort_order'));
    }
}
