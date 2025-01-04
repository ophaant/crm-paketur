<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyStoreRequest;
use App\Models\Company;
use App\Services\Company\CompanyService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    use ApiResponseTrait;

    protected $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    public function index(Request $request): JsonResponse
    {
        if(!auth()->user()->can('company-list'))
            return $this->error(config('rc.forbidden'), 403);

        $listCompany = $this->companyService->list($request);

        return $this->success($listCompany);
    }

    public function store(CompanyStoreRequest $request): JsonResponse
    {
        if(!auth()->user()->can('company-create'))
            return $this->error(config('rc.forbidden'), 403);

        $company = $this->companyService->create($request);

        return $this->success($company,201,config('rc.create_successfully'));
    }

    public function show(int $id): JsonResponse
    {
        if(!auth()->user()->can('company-show'))
            return $this->error(config('rc.forbidden'), 403);

        $company = $this->companyService->show($id);

        return $this->success($company);
    }
//
    public function update(CompanyStoreRequest $request, int $id): JsonResponse
    {
        if(!auth()->user()->can('company-edit'))
            return $this->error(config('rc.forbidden'), 403);

        $company = $this->companyService->update($id, $request);

        return $this->success($company, 200, config('rc.update_successfully'));
    }

    public function destroy(int $id): JsonResponse
    {
        if(!auth()->user()->can('company-delete'))
            return $this->error(config('rc.forbidden'), 403);

        $this->companyService->delete($id);

        return $this->success([], 200, config('rc.delete_successfully'));
    }
}
