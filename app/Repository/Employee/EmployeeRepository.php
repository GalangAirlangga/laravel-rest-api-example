<?php

namespace App\Repository\Employee;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class EmployeeRepository implements EmployeeRepositoryInterface
{
    protected Employee $model;

    public function __construct()
    {
        $this->model = new Employee();
    }

    /**
     * get all data employee
     * with filter first_name,last_name,email,phone number,hire date and trashed
     * with sort first_name,hire date,salary and id
     * default sort id DESC
     */
    public function all(): Employee|Builder
    {
        return $this->model::withPositionAndDepartment();

    }

    /**
     * get data employee by id
     * @param int $id
     * @return Employee|Builder
     */
    public function getById(int $id): Employee|Builder
    {
        $employeeData = $this->model::withPositionAndDepartment()
            ->with('jobHistories')
            ->find($id);
        if (!$employeeData) {
            throw new ModelNotFoundException('employee data not found');
        }
        return $employeeData;
    }

    /**
     * @param array $employee
     * @return Employee|Builder
     */
    public function create(array $employee): Employee|Builder
    {
        return $this->model::create($employee);
    }

    /**
     * update data employee
     * @param int $id
     * @param array $employee
     * @return Employee|Builder
     */
    public function update(int $id, array $employee): Employee|Builder
    {
        $employeeData = $this->getById($id);
        $employeeData->update($employee);
        return $employeeData;
    }

    /**
     * soft delete data employee
     * @param int $id
     * @return Employee|Builder
     */
    public function delete(int $id): Employee|Builder
    {
        $employeeData = $this->getById($id);
        $employeeData->delete();
        return $employeeData;
    }

    /**
     * @return Collection|array|LengthAwarePaginator
     */
    public function allWithFilter(): Collection|array|LengthAwarePaginator
    {

        return QueryBuilder::for($this->all())
            ->allowedFilters([
                'first_name',
                'last_name',
                'email',
                'phone_number',
                'hire_date',
                AllowedFilter::trashed(),
            ])
            ->defaultSort('-employees.id')
            ->allowedSorts('first_name', 'employees.id', 'hire_date', 'salary')
            ->paginate();
    }
}
