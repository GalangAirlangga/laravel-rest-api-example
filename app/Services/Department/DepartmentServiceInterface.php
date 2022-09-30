<?php

namespace App\Services\Department;

use App\Models\Department;
use Illuminate\Database\Eloquent\Collection;

interface DepartmentServiceInterface
{
    public function all(): Collection;

    public function getById(int $id): Department;

    public function create(array $department): Department;

    public function update(int $id, array $department): Department;

    public function delete(int $id): Department;
}
