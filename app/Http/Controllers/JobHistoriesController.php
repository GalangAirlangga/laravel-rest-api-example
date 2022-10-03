<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobHistory\CreateRequest;
use App\Http\Requests\JobHistory\UpdateRequest;
use App\Services\JobHistory\JobHistoryServiceInterface;
use App\Traits\RespondsWithHttpStatus;
use InvalidArgumentException;
use Log;
use Throwable;

class JobHistoriesController extends Controller
{
    use RespondsWithHttpStatus;

    private JobHistoryServiceInterface $jobHistoryService;

    public function __construct(JobHistoryServiceInterface $jobHistoryService)
    {
        $this->jobHistoryService = $jobHistoryService;
    }

    public function store(CreateRequest $request)
    {
        $validatedData = $request->safe()->only([
            'employee_id',
            'department_id',
            'position_id',
            'start_date',
            'end_date'
        ]);
        try {
            $jobHistory = $this->jobHistoryService->create($validatedData);
            return $this->success('successfully created job history data', $jobHistory);
        } catch (InvalidArgumentException $exception) {
            Log::error('job history create : ' . $exception->getMessage());
            return $this->failure($exception->getMessage(), 400);
        } catch (Throwable $exception) {
            Log::error('job history create : ' . $exception->getMessage());
            return $this->failure('error create job history', 400);
        }
    }

    public function show($idEmployee)
    {
        try {
            $jobHistory = $this->jobHistoryService->getByEmployee($idEmployee);
            return $this->success('successfully show job history data', $jobHistory);
        } catch (InvalidArgumentException $exception) {
            Log::error('job history show : ' . $exception->getMessage());
            return $this->failure($exception->getMessage(), 400);
        } catch (Throwable $exception) {
            Log::error('job history show : ' . $exception->getMessage());
            return $this->failure('error show job history', 400);
        }
    }


    public function update(UpdateRequest $request, $id)
    {
        $validatedData = $request->safe()->only([
            'employee_id',
            'department_id',
            'position_id',
            'start_date',
            'end_date'
        ]);
        try {
            $jobHistory = $this->jobHistoryService->update($validatedData, $id);
            return $this->success('successfully updated job history data', $jobHistory);
        } catch (InvalidArgumentException $exception) {
            Log::error('job history updated : ' . $exception->getMessage());
            return $this->failure($exception->getMessage(), 400);
        } catch (Throwable $exception) {
            Log::error('job history updated : ' . $exception->getMessage());
            return $this->failure('error updated job history', 400);
        }

    }

    public function destroy($id)
    {
        try {
            $job = $this->jobHistoryService->delete($id);
            return $this->success('successfully delete job history data', $job);
        } catch (InvalidArgumentException $exception) {
            Log::error('job history delete : ' . $exception->getMessage());
            return $this->failure($exception->getMessage(), 400);
        } catch (Throwable $exception) {
            Log::error('job history delete : ' . $exception->getMessage());
            return $this->failure('error delete job history', 400);
        }
    }
}
