<?php

namespace App\Repository\Employee;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

interface EmployeeRepositoryInterface
{
    public function all(): Employee|Builder;

    public function allWithFilter(): Collection|array;

    public function getById(int $id): Employee|Builder;

    public function create(array $employee): Employee|Builder;

    public function update(int $id, array $employee): Employee|Builder;

    public function delete(int $id): Employee|Builder;
}
