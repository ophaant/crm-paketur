<?php

namespace App\Services\Manager;

use App\Http\Resources\ManagerResource;
use Illuminate\Http\Request;
use LaravelEasyRepository\ServiceApi;
use App\Repositories\Manager\ManagerRepository;

class ManagerServiceImplement extends ServiceApi implements ManagerService{

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
     protected ManagerRepository $mainRepository;

    public function __construct(ManagerRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    public function list(Request $request): array
    {

        $managers = $this->mainRepository->list($request);

        if (!$managers['data'])
            abort(404);

        $response = [];

        foreach ($managers['data'] as  $manager) {
            $response['managers'][] = [
                'id' => $manager['id'],
                'name' => $manager['name'],
                'phone' => $manager['phone'],
                'address' => $manager['address']
            ];
        }

        $response['total'] = $managers['total'];
        $response['links'] = [
            'first' => $managers['first_page_url'],
            'prev' => $managers['prev_page_url'],
            'next' => $managers['next_page_url']
        ];

        return $response;

    }

    public function create(mixed $data): array
    {
        $manager = $this->mainRepository->create($data->validated());
        $manager->assignRole('manager');
        return ['manager' => new ManagerResource($manager)];

    }

    public function show(int $id): array
    {
        $manager = $this->mainRepository->findOrFail($id);
        return ['manager' => $manager->toArray()];
    }

    public function update(mixed $id, mixed $data): array
    {
        $manager = $this->mainRepository->findOrFail($id);
        $manager->update($data->validated());
        return ['manager' => new ManagerResource($manager)];
    }

    public function delete(mixed $id): void
    {
        $manager = $this->mainRepository->findOrFail($id);
        $manager->delete();
    }
}
