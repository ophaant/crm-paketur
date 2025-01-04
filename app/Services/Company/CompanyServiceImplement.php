<?php

namespace App\Services\Company;

use App\Http\Resources\CompanyResource;
use App\Http\Resources\EmployeeResource;
use App\Http\Resources\ManagerResource;
use App\Models\Company;
use Illuminate\Http\Request;
use LaravelEasyRepository\ServiceApi;
use App\Repositories\Company\CompanyRepository;

class CompanyServiceImplement extends ServiceApi implements CompanyService{

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
     protected CompanyRepository $mainRepository;

    public function __construct(CompanyRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    public function list(Request $request): array
    {

        $companies = $this->mainRepository->list($request);

        if (!$companies['data'])
            abort(404);

        $response = [];

        foreach ($companies['data'] as  $company) {
            $response['companies'][] = [
                'id' => $company['id'],
                'name' => $company['name'],
                'email' => $company['email'],
                'phone' => $company['phone'],
            ];
        }

        $response['total'] = $companies['total'];
        $response['links'] = [
            'first' => $companies['first_page_url'],
            'prev' => $companies['prev_page_url'],
            'next' => $companies['next_page_url']
        ];

        return $response;

    }

    public function create(mixed $data): array
    {
        $company = $this->mainRepository->create($data->validated());

        $password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()'), 0, 12);
        $manajer = $company->users()->create([
            'name' => 'Manager-'.$company->name,
            'email' => 'manager-'.substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_'), 0, 12).'@'.substr(strrchr($company->email, "@"), 1),
            'password' => bcrypt($password),
        ]);
        $manajer->assignRole('manager');

        $company = new CompanyResource($company);

        $credentials = [
            'email' => $manajer['email'],
            'password' => $password
        ];
        $manajer['password'] = $password;
        $company->manager = $credentials;

        return ['company' => $company];

    }

    public function show(int $id): array
    {
        $company = $this->mainRepository->findOrFail($id);
        return ['company' => $company->toArray()];
    }

    public function update(mixed $id, mixed $data): array
    {
        $company = $this->mainRepository->findOrFail($id);
        $company->update($data->validated());
        return ['company' => new CompanyResource($company)];
    }

    public function delete(mixed $id): void
    {
        $company = $this->mainRepository->findOrFail($id);
        $company->delete();
    }
}
