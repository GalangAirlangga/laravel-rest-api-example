<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Throwable;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

    public function testGetAll(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['employee-index']
        );
        Position::factory()->count(10)->create();
        Department::factory()->count(10)->create();
        Employee::factory()->count(10)->create([
            'position_id' => Position::all()->random()->id,
            'department_id' => Department::all()->random()->id,
        ]);
        $response = $this->get('/api/v1/employees');

        $response->assertJson([
            'success' => true,
            'data' => array(),
            'message' => "data all employee"
        ])
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'first_name',
                        'last_name',
                        'email',
                        'phone_number',
                        'salary',
                        'hire_date'
                    ]
                ],
                'message'
            ]);
    }

    /**
     * @throws Throwable
     */
    public function testCreateData(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['employee-create']
        );
        Position::factory()->count(10)->create();
        Department::factory()->count(10)->create();
        $position_id = Position::all()->random()->id;
        $department_id = Department::all()->random()->id;
        $response = $this->postJson('/api/v1/employees', [
            'first_name' => 'Zoro',
            'last_name' => 'Roronoa',
            'email' => 'zoro@email.com',
            'phone_number' => '81281281',
            'salary' => 700,
            'hire_date' => date('y-m-d'),
            'position_id' => $position_id,
            'department_id' => $department_id,
            'start_date' => Carbon::now()->format('Y-m-d'),
            'end_date' => Carbon::now()->addYears(2)->format('Y-m-d')
        ]);

        $response->assertJson([
            'success' => true,
            'data' => array(),
            'message' => "successfully created employee data"
        ]);
        $this->assertDatabaseHas('employees', [
            'first_name' => 'Zoro',
            'last_name' => 'Roronoa',
            'email' => 'zoro@email.com',
            'phone_number' => '81281281',
            'salary' => 700,
            'hire_date' => date('y-m-d'),
            'position_id' => $position_id,
            'department_id' => $department_id,
        ]);
        $this->assertDatabaseHas('job_histories', [
            'employee_id' => $response->decodeResponseJson()['data']['id'],
            'position_id' => $position_id,
            'department_id' => $department_id,
            'start_date' => Carbon::now()->format('Y-m-d'),
            'end_date' => Carbon::now()->addYears(2)->format('Y-m-d')
        ]);
    }

    public function testUpdateData(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['employee-edit']
        );
        Position::factory()->count(10)->create();
        Department::factory()->count(10)->create();
        $employee = (new Employee)->create([
            'first_name' => 'Zoro',
            'last_name' => 'Roronoa',
            'email' => 'zoro@email.com',
            'phone_number' => '81281281',
            'salary' => 700,
            'hire_date' => date('y-m-d'),
            'position_id' => Position::all()->random()->id,
            'department_id' => Department::all()->random()->id
        ]);
        $response = $this->putJson('/api/v1/employees/' . $employee->id, [
            'first_name' => 'Zoro',
            'last_name' => 'Roronoa',
            'email' => 'roronoa-zoro@email.com',
            'phone_number' => '81281281',
            'salary' => 800,
            'hire_date' => date('y-m-d')
        ]);

        $response->assertJson([
            'success' => true,
            'data' => [
                'id' => $employee->id,
                'first_name' => 'Zoro',
                'last_name' => 'Roronoa',
                'email' => 'roronoa-zoro@email.com',
                'phone_number' => '81281281',
                'salary' => 800,
                'hire_date' => date('y-m-d')
            ],
            'message' => "successfully update employee data"
        ]);
        $this->assertDatabaseHas('employees', [
            'first_name' => 'Zoro',
            'last_name' => 'Roronoa',
            'email' => 'roronoa-zoro@email.com',
            'phone_number' => '81281281',
            'salary' => 800,
            'hire_date' => date('y-m-d')
        ]);
    }

    public function testShowData(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['employee-show']
        );
        Position::factory()->count(10)->create();
        Department::factory()->count(10)->create();

        $employee = (new Employee)->create([
            'first_name' => 'Zoro',
            'last_name' => 'Roronoa',
            'email' => 'roronoa-zoro@email.com',
            'phone_number' => '81281281',
            'salary' => 800,
            'hire_date' => date('y-m-d'),
            'position_id' => Position::all()->random()->id,
            'department_id' => Department::all()->random()->id
        ]);
        $response = $this->getJson('/api/v1/employees/' . $employee->id . '/show');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'first_name' => $employee->first_name,
                    'last_name' => $employee->last_name,
                    'email' => $employee->email,
                    'phone_number' => $employee->phone_number,
                    'salary' => $employee->salary,
                    'hire_date' => $employee->hire_date
                ],
                'message' => "successfully show employee data"
            ]);
    }

    public function testDeleteData(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['employee-delete']
        );
        Position::factory()->count(10)->create();
        Department::factory()->count(10)->create();

        $employee = (new Employee)->create([
            'first_name' => 'Zoro',
            'last_name' => 'Roronoa',
            'email' => 'roronoa-zoro@email.com',
            'phone_number' => '81281281',
            'salary' => 800,
            'hire_date' => date('y-m-d'),
            'position_id' => Position::all()->random()->id,
            'department_id' => Department::all()->random()->id
        ]);
        $response = $this->deleteJson('/api/v1/employees/' . $employee->id);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [],
                'message' => "successfully delete employee data"
            ]);
        $this->assertSoftDeleted($employee);
    }
}
