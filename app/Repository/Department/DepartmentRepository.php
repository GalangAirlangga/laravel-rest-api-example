<?php

namespace App\Repository\Department;

use App\Models\Department;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class DepartmentRepository implements DepartmentRepositoryInterface
{
    protected Department $model;

    public function __construct()
    {
        $this->model=new Department();
    }

    /**
     * get all data department
     * @return Department
     */
    public function all(): Department
    {
        return $this->model;
    }

    /**
     * get all data department
     * with filter name and trashed
     * with sort name and id
     * default sort id DESC
     * @return Collection
     */
    public function allWithFilter(): Collection
    {
        return QueryBuilder::for($this->all())
            ->allowedFilters([
                'name',
                AllowedFilter::trashed(),
            ])
            ->defaultSort('-id')
            ->allowedSorts('name', 'id')
            ->get();
    }

    /**
     * get data department by id
     * @param int $id
     * @return Department
     */
    public function getById(int $id): Department
    {
        $departmentData = $this->model::find($id);
        if (!$departmentData) {
            throw new ModelNotFoundException('department data not found');
        }
        return $departmentData;
    }

    /**
     * create data department
     * @param array $department
     * @return Department
     */
    public function create(array $department): Department
    {
        return $this->model::create($department);
    }

    /**
     * update data department
     * @param int $id
     * @param array $department
     * @return Department
     */
    public function update(int $id, array $department): Department
    {
        $departmentData = $this->getById($id);
        $departmentData->update($department);
        return $departmentData;
    }

    /**
     * soft delete data department
     * @param int $id
     * @return Department
     */
    public function delete(int $id): Department
    {
        $departmentData = $this->getById($id);
        $departmentData->delete();
        return $departmentData;
    }
}
