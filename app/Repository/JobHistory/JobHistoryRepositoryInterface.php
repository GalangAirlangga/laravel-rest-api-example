<?php

namespace App\Repository\JobHistory;

use App\Models\JobHistory;

interface JobHistoryRepositoryInterface
{
    public function getById(int $id): JobHistory;

    public function getByEmployeeId(int $id): JobHistory;

    public function create(array $job): JobHistory;

    public function update(array $job, int $id): JobHistory;

    public function delete(int $id): JobHistory;
}
