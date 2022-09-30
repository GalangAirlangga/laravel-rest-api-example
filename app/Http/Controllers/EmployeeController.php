<?php

namespace App\Http\Controllers;

use App\Http\Requests\Employee\CreateRequest;
use App\Http\Requests\Employee\UpdateRequest;
use App\Services\Employee\EmployeeServiceInterface;
use App\Traits\RespondsWithHttpStatus;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Log;
use Spatie\QueryBuilder\Exceptions\InvalidSortQuery;
use Throwable;

class EmployeeController extends Controller
{
    use RespondsWithHttpStatus;

    private EmployeeServiceInterface $employeeService;

    public function __construct(EmployeeServiceInterface $employeeService)
    {
        $this->employeeService = $employeeService;
    }

    public function index(): Response|Application|ResponseFactory
    {
        try {
            $employee = $this->employeeService->all();
            return $this->success('data all employee', $employee);
        } catch (InvalidSortQuery $exception) {
            return $this->failure($exception->getMessage(), 400);
        } catch (Throwable $exception) {
            Log::error('employee index : ' . $exception->getMessage());
            return $this->failure('error get employee', 400);
        }
    }


    public function store(CreateRequest $request): Response|Application|ResponseFactory
    {
        $employee = $request->safe()->only([
            'first_name',
            'last_name',
            'email',
            'phone_number',
            'hire_date',
            'salary',
            'department_id',
            'position_id',
        ]);
        $job = $request->safe()->only([
            'department_id',
            'position_id',
            'start_date',
            'end_date'
        ]);
        try {
            $employee = $this->employeeService->create($employee, $job);
            return $this->success('successfully created employee data', $employee);
        } catch (Throwable $exception) {
            Log::error('employee create : ' . $exception->getMessage());
            return $this->failure('error create employee', 400);
        }
    }

    public function show($id): Response|Application|ResponseFactory
    {
        try {
            $employee = $this->employeeService->getById($id);
            return $this->success('successfully show employee data', $employee);
        } catch (ModelNotFoundException $exception) {
            return $this->failure($exception->getMessage(), 404);
        } catch (Throwable $exception) {
            Log::error('employee show : ' . $exception->getMessage());
            return $this->failure('error show employee', 400);
        }
    }

    public function update(UpdateRequest $request, $id): Response|Application|ResponseFactory
    {
        $validatedData = $request->safe()->only([
            'first_name',
            'last_name',
            'email',
            'phone_number',
            'hire_date',
            'salary'
        ]);
        try {
            $employee = $this->employeeService->update($id, $validatedData);
            return $this->success('successfully update employee data', $employee);
        } catch (Throwable $exception) {
            Log::error('employee update : ' . $exception->getMessage());
            return $this->failure('error update employee', 400);
        }
    }

    public function destroy($id): Response|Application|ResponseFactory
    {
        try {
            $this->employeeService->delete($id);
            return $this->success('successfully delete employee data');
        } catch (ModelNotFoundException $exception) {
            return $this->failure($exception->getMessage(), 404);
        } catch (Throwable $exception) {
            Log::error('employee delete : ' . $exception->getMessage());
            return $this->failure('error delete employee', 400);
        }
    }
}
