<?php

namespace App\Services\Department;

use App\Models\Department;
use App\Repository\Department\DepartmentRepositoryInterface;
use DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use InvalidArgumentException;
use Log;
use Spatie\QueryBuilder\Exceptions\InvalidSortQuery;
use Throwable;

class DepartmentService implements DepartmentServiceInterface
{
    private DepartmentRepositoryInterface $departmentRepository;

    public function __construct(DepartmentRepositoryInterface $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
    }

    /**
     * this function for get all data department
     * @return Collection
     * @throws Throwable
     */
    public function all(): Collection
    {
        DB::beginTransaction();
        try {
            $department = $this->departmentRepository->allWithFilter();
            DB::commit();
            return $department;
        } catch (InvalidSortQuery $exception) {
            DB::rollBack();
            throw  new InvalidSortQuery($exception->unknownSorts, $exception->allowedSorts);
        } catch (Throwable $exception) {
            DB::rollBack();
            Log::error('all department service : ' . $exception->getMessage());
            throw new InvalidArgumentException('Unable to get all department data');
        }
    }

    /**
     * this function for get data department by id
     * @param int $id
     * @return Department
     * @throws Throwable
     */
    public function getById(int $id): Department
    {
        DB::beginTransaction();
        try {
            $department = $this->departmentRepository->getById($id);
            DB::commit();
            return $department;
        } catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        } catch (Throwable $exception) {
            DB::rollBack();
            Log::error('getById department service : ' . $exception->getMessage(), $exception->getTrace());
            throw new InvalidArgumentException('Unable to getById department data');
        }
    }


    /**
     * this function for create data department
     * @param array $department
     * @return Department
     * @throws Throwable
     */
    public function create(array $department): Department
    {
        DB::beginTransaction();
        try {
            $department = $this->departmentRepository->create($department);
            DB::commit();
            return $department;
        } catch (Throwable $exception) {
            DB::rollBack();
            Log::error('create department service : ' . $exception->getMessage());
            throw new InvalidArgumentException('Unable to create department data');
        }
    }

    /**
     * this function for update data department
     * @param int $id
     * @param array $department
     * @return Department
     * @throws Throwable
     */
    public function update(int $id, array $department): Department
    {
        DB::beginTransaction();
        try {
            $departmentData = $this->departmentRepository->update($id, $department);
            DB::commit();
            return $departmentData;
        } catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        } catch (Throwable $exception) {
            DB::rollBack();
            Log::error('update department service : ' . $exception->getMessage());
            throw new InvalidArgumentException('Unable to update department data');
        }
    }

    /**
     * this function for delete data department by id
     * @param int $id
     * @return Department
     * @throws Throwable
     */
    public function delete(int $id): Department
    {
        DB::beginTransaction();
        try {
            $department = $this->departmentRepository->delete($id);
            DB::commit();
            return $department;
        } catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        } catch (Throwable $exception) {
            DB::rollBack();
            Log::error('delete department service : ' . $exception->getMessage());
            throw new InvalidArgumentException('Unable to delete department data');
        }
    }
}
