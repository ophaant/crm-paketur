<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeShowRequest;
use App\Http\Requests\EmployeeStoreRequest;
use App\Http\Requests\QueryParamsRequest;
use App\Services\Employee\EmployeeService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    use ApiResponseTrait;
    protected $employeeService;

    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }

    public function index(QueryParamsRequest $request): JsonResponse
    {
        if(!auth()->user()->can('employee-list'))
            return $this->error(config('rc.forbidden'), 403);

        $listEmployee = $this->employeeService->list($request);

        return $this->success($listEmployee);
    }

    public function store(EmployeeStoreRequest $request): JsonResponse
    {
        if(!auth()->user()->can('employee-create'))
            return $this->error(config('rc.forbidden'), 403);

        $employee = $this->employeeService->create($request);

        return $this->success($employee,201,config('rc.create_successfully'));
    }

    public function show(int $id): JsonResponse
    {
        if(!auth()->user()->can('employee-show'))
            return $this->error(config('rc.forbidden'), 403);

        $employee = $this->employeeService->show($id);

        return $this->success($employee);
    }

    public function update(EmployeeStoreRequest $request, int $id): JsonResponse
    {
        if(!auth()->user()->can('employee-edit'))
            return $this->error(config('rc.forbidden'), 403);

        $employee = $this->employeeService->update($id, $request);

        return $this->success($employee, 200, config('rc.update_successfully'));
    }

    public function destroy(int $id): JsonResponse
    {
        if(!auth()->user()->can('employee-delete'))
            return $this->error(config('rc.forbidden'), 403);

        $this->employeeService->delete($id);

        return $this->success([], 200, config('rc.delete_successfully'));
    }
}
