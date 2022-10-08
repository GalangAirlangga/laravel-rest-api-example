<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Str;
use Tests\TestCase;

class DepartmentTest extends TestCase
{
    use RefreshDatabase;

    public function testGetAll(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['department-index']
        );
        Department::factory(10)->create();
        $response = $this->get('/api/v1/departments');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => array(),
                'message' => "data all department"
            ])
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description'
                    ]
                ],
                'message'
            ]);
    }

    public function testCreateData(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['department-create']
        );
        $response = $this->postJson('/api/v1/departments', [
            'name' => 'Database Administrator',
            'description' => 'Database Administrator',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => array(),
                'message' => "successfully created department data"
            ]);
        $this->assertDatabaseHas('departments', [
            'name' => 'Database Administrator',
        ]);
    }

    public function testCreateDataRequiredName(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['department-create']
        );
        $response = $this->postJson('/api/v1/departments', [
            'name' => '',
            'description' => 'Database Administrator',
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'errors' => array(
                    "name" => array(
                        "The name must be a string.",
                        "The name field is required."
                    )
                ),
                'message' => "Validation error"
            ]);
    }

    public function testCreateDataNameMoreOverValidation(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['department-create']
        );
        $response = $this->postJson('/api/v1/departments', [
            'name' => Str::random(300),
            'description' => 'Database Administrator',
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'errors' => array(
                    "name" => array(
                        "The name must not be greater than 255 characters."
                    )
                ),
                'message' => "Validation error"
            ]);
    }

    public function testCreateDataEmptyDescription(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['department-create']
        );
        $response = $this->postJson('/api/v1/departments', [
            'name' => 'UI / UX',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => array(),
                'message' => "successfully created department data"
            ]);
        $this->assertDatabaseHas('departments', [
            'name' => 'UI / UX',
        ]);
    }

    public function testUpdateData(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['department-edit']
        );
        $department = (new Department)->create([
            'name' => 'IT Support',
            'description' => 'description'
        ]);
        $response = $this->putJson('/api/v1/departments/' . $department->id, [
            'name' => 'IT Support',
            'description' => 'description update',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $department->id,
                    'name' => 'IT Support',
                    'description' => 'description update',
                ],
                'message' => "successfully update department data"
            ]);
        $this->assertDatabaseHas('departments', [
            'id' => $department->id,
            'name' => 'IT Support',
            'description' => 'description update',
        ]);
    }

    public function testShowData(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['department-show']
        );
        $department = (new Department)->create([
            'name' => 'IT Developer',
            'description' => 'description'
        ]);
        $response = $this->getJson('/api/v1/departments/' . $department->id . '/show');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $department->id,
                    'name' => $department->name,
                    'description' => $department->description,
                ],
                'message' => "successfully show department data"
            ]);
    }

    public function testDeleteData(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['department-delete']
        );
        $department = (new Department)->create([
            'name' => 'Backend Developer',
            'description' => 'description'
        ]);
        $response = $this->deleteJson('/api/v1/departments/' . $department->id);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [],
                'message' => "successfully delete department data"
            ]);
        $this->assertSoftDeleted($department);
    }
}
