<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeStoreRequest;
use App\Http\Requests\ManagerStoreRequest;
use App\Services\Manager\ManagerService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    use ApiResponseTrait;

    protected $managerService;

    public function __construct(ManagerService $managerService)
    {
        $this->managerService = $managerService;
    }

    public function index(Request $request): JsonResponse
    {
        if(!auth()->user()->can('manager-list'))
            return $this->error(config('rc.forbidden'), 403);

        $listmanager = $this->managerService->list($request);

        return $this->success($listmanager);
    }

    public function store(ManagerStoreRequest $request): JsonResponse
    {
        if(!auth()->user()->can('manager-create'))
            return $this->error(config('rc.forbidden'), 403);

        $manager = $this->managerService->create($request);

        return $this->success($manager,201,config('rc.create_successfully'));
    }

    public function show(int $id): JsonResponse
    {
        if(!auth()->user()->can('manager-show'))
            return $this->error(config('rc.forbidden'), 403);

        $manager = $this->managerService->show($id);

        return $this->success($manager);
    }

    public function update(ManagerStoreRequest $request, int $id): JsonResponse
    {
        if(!auth()->user()->can('manager-edit'))
            return $this->error(config('rc.forbidden'), 403);

        $manager = $this->managerService->update($id, $request);

        return $this->success($manager, 200, config('rc.update_successfully'));
    }

    public function destroy(int $id): JsonResponse
    {
        if(!auth()->user()->can('manager-delete'))
            return $this->error(config('rc.forbidden'), 403);

        $this->managerService->delete($id);

        return $this->success([], 200, config('rc.delete_successfully'));
    }
}
