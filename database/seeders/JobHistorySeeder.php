<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\JobHistory;
use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class JobHistorySeeder extends Seeder
{
    public function run()
    {
        $employee = Employee::get()->random(700);
        collect($employee)->each(fn($data) => JobHistory::factory()->count(1)->state(new Sequence(
            fn($sequence) => [
                'employee_id' => $data->id,
                'position_id' => Position::all()->random(),
                'department_id' => Department::all()->random(),
                'start_date' => '2019-01-01',
                'end_date' => '2020-01-01',
            ],
        ))->create());
        collect($employee)->each(fn($data) => JobHistory::factory()->count(1)->state(new Sequence(
            fn($sequence) => [
                'employee_id' => $data->id,
                'position_id' => Position::all()->random(),
                'department_id' => Department::all()->random(),
                'start_date' => '2020-01-02',
                'end_date' => '2021-01-01',
            ],
        ))->create());
        collect($employee)->each(fn($data) => JobHistory::factory()->count(1)->state(new Sequence(
            fn($sequence) => [
                'employee_id' => $data->id,
                'position_id' => Position::all()->random(),
                'department_id' => Department::all()->random(),
                'start_date' => '2021-01-02',
                'end_date' => '2022-01-01',
            ],
        ))->create());

    }
}
