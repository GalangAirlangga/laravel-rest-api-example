<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Employee;
use App\Models\JobHistory;
use App\Models\Position;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class JobHistoryTest extends TestCase
{

    use RefreshDatabase;

    public function testShowByEmployeeId(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['jobHistory-show']
        );
        Position::factory()->count(10)->create();
        Department::factory()->count(10)->create();
        Employee::factory()->count(1)->has(JobHistory::factory()->count(1)->state(function (array $attributes, Employee $employee) {
            return [
                'position_id' => $employee->position_id,
                'department_id' => $employee->department_id,
                'start_date' => '2022-01-02',
                'end_date' => '2023-01-01'
            ];
        }))
            ->state(function (array $attributes) {
                return [
                    'position_id' => Position::all()->random(),
                    'department_id' => Department::all()->random(),
                ];
            })->create();
        $employee = Employee::first();
        $response = $this->get('/api/v1/jobs-history/' . $employee->id . '/employee');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => array(),
                'message' => "successfully show job history data"
            ])
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'start_date',
                        'end_date',
                        'employee_id',
                        'department_id',
                        'position_id',
                    ]
                ],
                'message'
            ]);
    }

    public function testCreateJobHistory(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['jobHistory-create']
        );
        Position::factory()->count(10)->create();
        Department::factory()->count(10)->create();
        Employee::factory()->count(1)->has(JobHistory::factory()->count(1)->state(function (array $attributes, Employee $employee) {
            return [
                'position_id' => $employee->position_id,
                'department_id' => $employee->department_id,
                'start_date' => '2022-01-02',
                'end_date' => '2023-01-01'
            ];
        }))
            ->state(function (array $attributes) {
                return [
                    'position_id' => Position::all()->random(),
                    'department_id' => Department::all()->random(),
                ];
            })->create();
        $employee = Employee::first();
        $position = Position::all()->random()->first();
        $department = Department::all()->random()->first();
        $response = $this->postJson('/api/v1/jobs-history', [
            'employee_id' => $employee->id,
            'position_id' => $position->id,
            'department_id' => $department->id,
            'start_date' => '2019-01-01',
            'end_date' => '2020-01-01',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => array(),
                'message' => "successfully created job history data"
            ]);
        $this->assertDatabaseHas('job_histories', [
            'employee_id' => $employee->id,
            'position_id' => $position->id,
            'department_id' => $department->id,
            'start_date' => '2019-01-01',
            'end_date' => '2020-01-01',
        ]);
    }

    public function testUpdateJobHistory(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['jobHistory-update']
        );
        Position::factory()->count(10)->create();
        Department::factory()->count(10)->create();
        Employee::factory()->count(1)->has(JobHistory::factory()->count(1)->state(function (array $attributes, Employee $employee) {
            return [
                'position_id' => $employee->position_id,
                'department_id' => $employee->department_id,
                'start_date' => '2022-01-02',
                'end_date' => '2023-01-01'
            ];
        }))
            ->state(function (array $attributes) {
                return [
                    'position_id' => Position::all()->random(),
                    'department_id' => Department::all()->random(),
                ];
            })->create();
        $employee = Employee::first();
        $position = Position::all()->random()->first();
        $department = Department::all()->random()->first();
        $jobHistory = JobHistory::create([
            'employee_id' => $employee->id,
            'position_id' => $position->id,
            'department_id' => $department->id,
            'start_date' => '2019-01-01',
            'end_date' => '2020-01-01',
        ]);
        $response = $this->putJson('/api/v1/jobs-history/' . $jobHistory->id, [
            'employee_id' => $employee->id,
            'position_id' => $position->id,
            'department_id' => $department->id,
            'start_date' => '2018-01-01',
            'end_date' => '2018-12-01',
        ]);

        $response
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $jobHistory->id,
                    'start_date' => '2018-01-01',
                    'end_date' => '2018-12-01',
                    'employee_id' => $employee->id,
                    'department_id' => $department->id,
                    'position_id' => $position->id,
                ],
                'message' => "successfully updated job history data"
            ]);
        $this->assertDatabaseHas('job_histories', [
            'id' => $jobHistory->id,
            'start_date' => '2018-01-01',
            'end_date' => '2018-12-01',
            'employee_id' => $employee->id,
            'department_id' => $department->id,
            'position_id' => $position->id,
        ]);
    }

    public function testDeleteJobHistory(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['jobHistory-delete']
        );
        Position::factory()->count(10)->create();
        Department::factory()->count(10)->create();
        Employee::factory()->count(1)->has(JobHistory::factory()->count(1)->state(function (array $attributes, Employee $employee) {
            return [
                'position_id' => $employee->position_id,
                'department_id' => $employee->department_id,
                'start_date' => '2022-01-02',
                'end_date' => '2023-01-01'
            ];
        }))
            ->state(function (array $attributes) {
                return [
                    'position_id' => Position::all()->random(),
                    'department_id' => Department::all()->random(),
                ];
            })->create();
        $employee = Employee::first();
        $position = Position::all()->random()->first();
        $department = Department::all()->random()->first();
        $jobHistory = JobHistory::create([
            'employee_id' => $employee->id,
            'position_id' => $position->id,
            'department_id' => $department->id,
            'start_date' => '2019-01-01',
            'end_date' => '2020-01-01',
        ]);
        $response = $this->deleteJson('/api/v1/jobs-history/' . $jobHistory->id);

        $response
            ->assertJson([
                'success' => true,
                'data' => array(),
                'message' => "successfully delete job history data"
            ]);
        $this->assertSoftDeleted($jobHistory);
    }
}
