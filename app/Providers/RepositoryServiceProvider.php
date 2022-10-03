<?php

namespace App\Providers;

use App\Repository\Auth\AuthRepository;
use App\Repository\Auth\AuthRepositoryInterface;
use App\Repository\Department\DepartmentRepository;
use App\Repository\Department\DepartmentRepositoryInterface;
use App\Repository\Employee\EmployeeRepository;
use App\Repository\Employee\EmployeeRepositoryInterface;
use App\Repository\JobHistory\JobHistoryRepository;
use App\Repository\JobHistory\JobHistoryRepositoryInterface;
use App\Repository\Position\PositionRepository;
use App\Repository\Position\PositionRepositoryInterface;
use App\Services\Auth\AuthService;
use App\Services\Auth\AuthServiceInterface;
use App\Services\Department\DepartmentService;
use App\Services\Department\DepartmentServiceInterface;
use App\Services\Employee\EmployeeService;
use App\Services\Employee\EmployeeServiceInterface;
use App\Services\JobHistory\JobHistoryService;
use App\Services\JobHistory\JobHistoryServiceInterface;
use App\Services\Position\PositionService;
use App\Services\Position\PositionServiceInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //repository
        $this->app->bind(DepartmentRepositoryInterface::class, DepartmentRepository::class);
        $this->app->bind(PositionRepositoryInterface::class, PositionRepository::class);
        $this->app->bind(EmployeeRepositoryInterface::class, EmployeeRepository::class);
        $this->app->bind(JobHistoryRepositoryInterface::class, JobHistoryRepository::class);
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        //service
        $this->app->bind(DepartmentServiceInterface::class, DepartmentService::class);
        $this->app->bind(PositionServiceInterface::class, PositionService::class);
        $this->app->bind(JobHistoryServiceInterface::class, JobHistoryService::class);
        $this->app->bind(EmployeeServiceInterface::class, EmployeeService::class);
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
