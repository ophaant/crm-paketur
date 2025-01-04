<?php

namespace App\Services\Employee;

use App\Http\Resources\EmployeeResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use LaravelEasyRepository\ServiceApi;
use App\Repositories\Employee\EmployeeRepository;

class EmployeeServiceImplement extends ServiceApi implements EmployeeService{

    /**
     * set title message api for CRUD
     * @param string $title
     */
     protected string $title = "";
     /**
     * uncomment this to override the default message
     * protected string $create_message = "";
     * protected string $update_message = "";
     * protected string $delete_message = "";
     */

     /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
     protected EmployeeRepository $mainRepository;

    public function __construct(EmployeeRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    public function list(Request $request): array
    {

            $employees = $this->mainRepository->list($request);

            if (!$employees['data'])
                abort(404);

            $response = [];

            foreach ($employees['data'] as  $employee) {
                $response['employees'][] = [
                    'id' => $employee['id'],
                    'name' => $employee['name'],
                    'phone' => $employee['phone'],
                    'address' => $employee['address']
                ];
            }

            $response['total'] = $employees['total'];
            $response['links'] = [
                'first' => $employees['first_page_url'],
                'prev' => $employees['prev_page_url'],
                'next' => $employees['next_page_url']
            ];

            return $response;

    }

    public function create(mixed $data): array
    {
            $employee = $this->mainRepository->create($data->validated());
            $employee->assignRole('employee');
            return ['employee' => new EmployeeResource($employee)];

    }

    public function show(int $id): array
    {
            $employee = $this->mainRepository->findEmployeeById($id);
            return ['employee' => $employee->toArray()];
    }

    public function update(mixed $id, mixed $data): array
    {
            $employee = $this->mainRepository->findEmployeeById($id);
            $employee->update($data->validated());
            return ['employee' => new EmployeeResource($employee)];
    }

    public function delete(mixed $id): void
    {
            $employee = $this->mainRepository->findEmployeeById($id);
            $employee->delete();
    }
}
