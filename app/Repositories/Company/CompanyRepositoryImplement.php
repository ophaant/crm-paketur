<?php

namespace App\Repositories\Company;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Company;

class CompanyRepositoryImplement extends Eloquent implements CompanyRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Company|mixed $model;
    */
    protected Company $model;

    public function __construct(Company $model)
    {
        $this->model = $model;
    }

    public function list(Request $request): array
    {
        return $this->companyBuilder($request)
            ->paginate($request->get('per_page', 10))
            ->withQueryString()
            ->toArray();
    }

    public function companyBuilder(Request $request): Builder
    {
        return $this->model->where(function ($query) use ($request){
            $query->filterByIlikeName($request->get('search'));
        })->sortBy($request->get('sort_by'), $request->get('sort_order'));
    }
}
