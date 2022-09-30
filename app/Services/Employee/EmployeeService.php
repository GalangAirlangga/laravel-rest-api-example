<?php

namespace App\Services\Employee;

use App\Models\Employee;
use App\Repository\Employee\EmployeeRepositoryInterface;
use App\Repository\JobHistory\JobHistoryRepositoryInterface;
use DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use InvalidArgumentException;
use Log;
use Spatie\QueryBuilder\Exceptions\InvalidSortQuery;
use Throwable;

class EmployeeService implements EmployeeServiceInterface
{
    private EmployeeRepositoryInterface $employeeRepository;
    private JobHistoryRepositoryInterface $jobHistoryRepository;

    public function __construct(EmployeeRepositoryInterface $employeeRepository, JobHistoryRepositoryInterface $jobHistoryRepository)
    {
        $this->employeeRepository = $employeeRepository;
        $this->jobHistoryRepository = $jobHistoryRepository;
    }


    /**
     * @return Collection|array
     * @throws Throwable
     */
    public function all(): Collection|array
    {
        DB::beginTransaction();
        try {
            $employee = $this->employeeRepository->allWithFilter();
            DB::commit();
            return $employee;
        } catch (InvalidSortQuery $exception) {
            DB::rollBack();
            throw  new InvalidSortQuery($exception->unknownSorts, $exception->allowedSorts);
        } catch (Throwable $exception) {
            DB::rollBack();
            Log::error('all employee service : ' . $exception->getMessage(), (array)$exception);
            throw new InvalidArgumentException('Unable to get all employee data');
        }
    }

    /**
     * this function for get data employee by id
     * @param int $id
     * @return Employee
     * @throws Throwable
     */
    public function getById(int $id): Employee
    {
        DB::beginTransaction();
        try {
            $employee = $this->employeeRepository->getById($id);
            DB::commit();
            return $employee;
        } catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        } catch (Throwable $exception) {
            DB::rollBack();
            Log::error('getById employee service : ' . $exception->getMessage());
            throw new InvalidArgumentException('Unable to getById employee data');
        }
    }

    /**
     * @param array $employee
     * @param array $job
     * @return Employee
     * @throws Throwable
     */
    public function create(array $employee, array $job): Employee
    {
        DB::beginTransaction();
        try {
            $employeeData = $this->employeeRepository->create($employee);
            $this->jobHistoryRepository->create([
                'employee_id' => $employeeData->id,
                'position_id' => $employeeData->position_id,
                'department_id' => $employeeData->department_id,
                'start_date' => $job['start_date'],
                'end_date' => $job['end_date'],

            ]);
            $result = $this->employeeRepository->getById($employeeData->id);
            DB::commit();
            return $result;
        } catch (Throwable $exception) {
            DB::rollBack();
            Log::error('create employee service : ' . $exception->getMessage());
            throw new InvalidArgumentException('Unable to create employee data');
        }
    }

    /**
     * @param int $id
     * @param array $employee
     * @return Employee
     * @throws Throwable
     */
    public function update(int $id, array $employee): Employee
    {
        DB::beginTransaction();
        try {
            $employeeData = $this->employeeRepository->update($id, $employee);
            DB::commit();
            return $employeeData;
        } catch (Throwable $exception) {
            DB::rollBack();
            Log::error('update employee service : ' . $exception->getMessage());
            throw new InvalidArgumentException('Unable to update employee data');
        }
    }

    /**
     * @param int $id
     * @return Employee
     * @throws Throwable
     */
    public function delete(int $id): Employee
    {
        DB::beginTransaction();
        try {
            $employee = $this->employeeRepository->delete($id);
            DB::commit();
            return $employee;
        } catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        } catch (Throwable $exception) {
            DB::rollBack();
            Log::error('delete employee service : ' . $exception->getMessage());
            throw new InvalidArgumentException('Unable to delete employee data');
        }
    }
}
