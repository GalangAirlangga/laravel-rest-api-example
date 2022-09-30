<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\JobHistory;
use App\Models\Position;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run()
    {

        Employee::factory()->count(1000)
            ->has(JobHistory::factory()->count(1)->state(function (array $attributes, Employee $employee) {
                return [
                    'position_id' => $employee->position_id,
                    'department_id'=>$employee->department_id,
                    'start_date' => '2022-01-02',
                    'end_date' => '2023-01-01'
                ];
            }))
            ->state(function (array $attributes) {
                return [
                    'position_id' => Position::all()->random(),
                    'department_id'=>Department::all()->random(),
                ];
            })->create();

    }
}
