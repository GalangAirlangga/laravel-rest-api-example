<?php

namespace App\Services\Employee;

use App\Models\Employee;

interface EmployeeServiceInterface
{
    public function all();

    public function getById(int $id): Employee;

    public function create(array $employee, array $job): Employee;

    public function update(int $id, array $employee): Employee;

    public function delete(int $id): Employee;
}
