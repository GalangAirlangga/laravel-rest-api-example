<?php

namespace App\Services\JobHistory;

use App\Models\JobHistory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

interface JobHistoryServiceInterface
{
    public function getByEmployee(int $idEmployee): Builder|JobHistory|Collection;

    public function create(array $job): JobHistory;

    public function update(array $job, int $id): JobHistory;

    public function delete(int $id): JobHistory;

    public function checkRangeDateJob(int $idEmployee, $start_date, $end_date): void;
}
