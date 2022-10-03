<?php

namespace App\Repository\JobHistory;

use App\Models\JobHistory;
use Illuminate\Database\Eloquent\Builder;

interface JobHistoryRepositoryInterface
{
    public function getById(int $id): JobHistory;

    public function getByEmployeeId(int $id): Builder|JobHistory;

    public function getCurrentJob(int $id, $start_date, $end_date): Builder|JobHistory;

    public function create(array $job): JobHistory;

    public function update(array $job, int $id): JobHistory;

    public function delete(int $id): JobHistory;
}
