<?php

namespace App\Repository\JobHistory;

use App\Models\JobHistory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class JobHistoryRepository implements JobHistoryRepositoryInterface
{
    protected JobHistory $model;

    public function __construct()
    {
        $this->model = new JobHistory();
    }

    /**
     * @param int $id
     * @return JobHistory
     */
    public function getById(int $id): JobHistory
    {
        $jobHistoryData = $this->model::find($id);
        if (!$jobHistoryData) {
            throw new ModelNotFoundException('job history data not found');
        }
        return $jobHistoryData;
    }

    /**
     * @param int $id
     * @return Builder|JobHistory
     */
    public function getByEmployeeId(int $id): Builder|JobHistory
    {
        $jobHistoryData = $this->model::where('employee_id', '=', $id);
        if (!$jobHistoryData) {
            throw new ModelNotFoundException('job history data not found');
        }
        return $jobHistoryData;
    }

    /**
     * @param array $job
     * @return JobHistory
     */
    public function create(array $job): JobHistory
    {
        return $this->model::create($job);
    }

    /**
     * @param array $job
     * @param int $id
     * @return JobHistory
     */
    public function update(array $job, int $id): JobHistory
    {
        $jobHistoryData = $this->getById($id);
        $jobHistoryData->update($job);
        return $jobHistoryData;
    }

    /**
     * @param int $id
     * @return JobHistory
     */
    public function delete(int $id): JobHistory
    {
        $jobHistoryData = $this->getById($id);
        $jobHistoryData->delete();
        return $jobHistoryData;
    }

    /**
     * @param int $id
     * @param $start_date
     * @param $end_date
     * @return Builder|JobHistory
     */
    public function getCurrentJob(int $id, $start_date, $end_date): Builder|JobHistory
    {
        return $this->getByEmployeeId($id)->whereRangeDate($start_date, $end_date);
    }
}
