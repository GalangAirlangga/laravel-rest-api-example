<?php

namespace App\Repository\Department;

use App\Models\Department;
use Illuminate\Database\Eloquent\Collection;

interface DepartmentRepositoryInterface
{
    public function all(): Department;

    public function allWithFilter(): Collection;

    public function getById(int $id): Department;

    public function create(array $department): Department;

    public function update(int $id, array $department): Department;

    public function delete(int $id): Department;
}
