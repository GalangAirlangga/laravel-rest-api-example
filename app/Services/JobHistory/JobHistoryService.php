<?php

namespace App\Services\JobHistory;

use App\Models\JobHistory;
use App\Repository\Employee\EmployeeRepositoryInterface;
use App\Repository\JobHistory\JobHistoryRepositoryInterface;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Throwable;

class JobHistoryService implements JobHistoryServiceInterface
{
    private JobHistoryRepositoryInterface $jobHistoryRepository;
    private EmployeeRepositoryInterface $employeeRepository;

    public function __construct(JobHistoryRepositoryInterface $jobHistoryRepository, EmployeeRepositoryInterface $employeeRepository)
    {
        $this->jobHistoryRepository = $jobHistoryRepository;
        $this->employeeRepository = $employeeRepository;
    }

    /**
     * @param int $idEmployee
     * @return JobHistory|Builder|Collection
     * @throws Throwable
     */
    public function getByEmployee(int $idEmployee): Builder|JobHistory|Collection
    {
        DB::beginTransaction();
        try {
            $jobsHistory = $this->jobHistoryRepository->getByEmployeeId($idEmployee)->get();
            DB::commit();
            return $jobsHistory;
        } catch (Throwable $exception) {
            DB::rollBack();
            Log::error('get by employee job history service : ' . $exception->getMessage());
            throw new InvalidArgumentException('Unable to get job history data');
        }
    }

    /**
     * @param array $job
     * @return JobHistory
     * @throws Throwable
     */
    public function create(array $job): JobHistory
    {
        DB::beginTransaction();
        try {
            //check if start_date is in the middle between start_date and end_date of existing jobs
            //this will solve the problem if employees have job history like this
            //start_date:2022-01-01
            //end_date:2022-12-01
            // and new start_date 2022-11-01 and end_date 2023-12-01
            // or new start_date 2021-01-01 and end_date 2022-01-05
            $this->checkRangeDateJob($job['employee_id'], $job['start_date'], $job['end_date']);
            //if job not found in range, job created
            $jobHistory = $this->jobHistoryRepository->create($job);
            //if now in the range of start date and end date
            //department positions and employees updated
            //otherwise it will just be history
            $now = date('Y-m-d');
            if (($now >= $job['start_date']) && ($now <= $job['end_date'])) {
                $this->employeeRepository->update($job['employee_id'], [
                    'position_id' => $job['position_id'],
                    'department_id' => $job['department_id']
                ]);
            }
            DB::commit();
            return $jobHistory;
        } catch (InvalidArgumentException $exception) {
            DB::rollBack();
            Log::error('create job history service : ' . $exception->getMessage());
            throw new InvalidArgumentException($exception->getMessage());
        } catch (Throwable $exception) {
            DB::rollBack();
            Log::error('create job history service : ' . $exception->getMessage());
            throw new InvalidArgumentException('Unable to create job history data');
        }

    }

    /**
     * @param array $job
     * @param int $id
     * @return JobHistory
     * @throws Throwable
     */
    public function update(array $job, int $id): JobHistory
    {
        DB::beginTransaction();
        try {
            //check whether the job to be updated is the current job?
            //if it's a job now then it can only update end_date
            //if it's a previous job then you can update all data
            $checkJob = $this->jobHistoryRepository->getById($id);
            Log::info('job : ', [$checkJob]);
            $now = date('Y-m-d');
            $dataHistory = array();
            if (($now >= $checkJob->start_date) && ($now <= $checkJob->end_date)) {
                Log::info('update end_date');
                //check if end_date is in the job date range of the employee
                $checkEndDate = $this->jobHistoryRepository->getCurrentJob($checkJob->employee_id, $job['end_date'], $job['end_date'])->where('id', '!=', $id)->first();
                if ($checkEndDate) {
                    throw new InvalidArgumentException("Employee have job start $checkEndDate->start_date - $checkEndDate->end_date");
                }
                $dataHistory = array(
                    'end_date' => $job['end_date'],
                );
            } else {
                Log::info('update full');
                $this->checkRangeDateJob($checkJob->employee_id, $job['start_date'], $job['end_date']);
                $dataHistory = $job;
            }

            DB::commit();
            return $this->jobHistoryRepository->update($dataHistory, $id);
        } catch (InvalidArgumentException $exception) {
            DB::rollBack();
            Log::error('updated job history service : ' . $exception->getMessage());
            throw new InvalidArgumentException($exception->getMessage());
        } catch (Throwable $exception) {
            DB::rollBack();
            Log::error('update job history service : ' . $exception->getMessage());
            throw new InvalidArgumentException('Unable to update job history data');
        }

    }

    /**
     * @param int $id
     * @return JobHistory
     * @throws Throwable
     */
    public function delete(int $id): JobHistory
    {
        DB::beginTransaction();
        try {
            $checkJob = $this->jobHistoryRepository->getById($id);
            $now = date('Y-m-d');
            //check if this work now ?
            //if yes then the data cannot be deleted,
            //otherwise the data will be deleted
            if (($now >= $checkJob->start_date) && ($now <= $checkJob->end_date)) {
                throw new InvalidArgumentException('can\'t delete this job history, because this job is currently active');
            }

            $jobHistory = $this->jobHistoryRepository->delete($id);
            DB::commit();
            return $jobHistory;
        } catch (InvalidArgumentException $exception) {
            DB::rollBack();
            Log::error('delete job history service : ' . $exception->getMessage());
            throw new InvalidArgumentException($exception->getMessage());
        } catch (Throwable $exception) {
            DB::rollBack();
            Log::error('update job history service : ' . $exception->getMessage());
            throw new InvalidArgumentException('Unable to update job history data');
        }

    }

    /**
     * @param int $idEmployee
     * @param $start_date
     * @param $end_date
     * @return void
     */
    public function checkRangeDateJob(int $idEmployee, $start_date, $end_date): void
    {
        $checkStartDate = $this->jobHistoryRepository->getCurrentJob($idEmployee, $start_date, $start_date)->first();
        $checkEndDate = $this->jobHistoryRepository->getCurrentJob($idEmployee, $end_date, $end_date)->first();
        Log::info('check range',
            [
                'start_date' => $checkStartDate,
                'end_date' => $checkEndDate
            ]);
        if ($checkStartDate) {
            throw new InvalidArgumentException("Employee have job start $checkStartDate->start_date - $checkStartDate->end_date");
        }
        if ($checkEndDate) {
            throw new InvalidArgumentException("Employee have job start $checkEndDate->start_date - $checkEndDate->end_date");
        }
    }
}
