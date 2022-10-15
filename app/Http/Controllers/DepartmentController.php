<?php

namespace App\Http\Controllers;

use App\Http\Requests\Department\CreateRequest;
use App\Http\Requests\Department\UpdateRequest;
use App\Services\Department\DepartmentServiceInterface;
use App\Traits\RespondsWithHttpStatus;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Log;
use Spatie\QueryBuilder\Exceptions\InvalidSortQuery;
use Throwable;

class DepartmentController extends Controller
{
    use RespondsWithHttpStatus;

    private DepartmentServiceInterface $departmentService;

    public function __construct(DepartmentServiceInterface $departmentService)
    {
        $this->departmentService = $departmentService;
    }

    public function index(): Response|Application|ResponseFactory
    {
        try {
            $department = $this->departmentService->all();
            return $this->success('data all department', $department);
        } catch (InvalidSortQuery $exception) {
            return $this->failure($exception->getMessage(), 400);
        } catch (Throwable $exception) {
            Log::error('department index : ' . $exception->getMessage());
            return $this->failure('error get department', 400);
        }
    }

    public function store(CreateRequest $request): Response|Application|ResponseFactory
    {
        $validatedData = $request->safe()->only([
            'name',
            'description'
        ]);
        try {
            $department = $this->departmentService->create($validatedData);
            return $this->success('successfully created department data', $department);
        } catch (Throwable $exception) {
            Log::error('department create : ' . $exception->getMessage());
            return $this->failure('error create department', 400);
        }
    }

    public function show($id): Response|Application|ResponseFactory
    {
        try {
            $department = $this->departmentService->getById($id);
            return $this->success('successfully show department data', $department);
        } catch (ModelNotFoundException $exception) {
            return $this->failure($exception->getMessage(), 404);
        } catch (Throwable $exception) {
            Log::error('department show : ' . $exception->getMessage());
            return $this->failure('error show department', 400);
        }
    }

    public function update(UpdateRequest $request, $id): Response|Application|ResponseFactory
    {
        $validatedData = $request->safe()->only([
            'name',
            'description'
        ]);
        try {
            $department = $this->departmentService->update($id, $validatedData);
            return $this->success('successfully update department data', $department);
        } catch (ModelNotFoundException $exception) {
            return $this->failure($exception->getMessage(), 404);
        } catch (Throwable $exception) {
            Log::error('department update : ' . $exception->getMessage());
            return $this->failure('error update department', 400);
        }
    }

    public function destroy($id): Response|Application|ResponseFactory
    {
        try {
            $this->departmentService->delete($id);
            return $this->success('successfully delete department data');
        } catch (ModelNotFoundException $exception) {
            return $this->failure($exception->getMessage(), 404);
        } catch (Throwable $exception) {
            Log::error('department delete : ' . $exception->getMessage());
            return $this->failure('error delete department', 400);
        }
    }
}
