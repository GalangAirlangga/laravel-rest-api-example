<?php

namespace Tests\Feature;

use App\Models\Position;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PositionTest extends TestCase
{
    use RefreshDatabase;

    public function testGetAll(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['position-index']
        );
        Position::factory(10)->create();
        $response = $this->get('/api/v1/positions');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => array(),
                'message' => "data all position"
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
            ['position-create']
        );
        $response = $this->postJson('/api/v1/positions', [
            'name' => 'Database Administrator',
            'description' => 'Database Administrator',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => array(),
                'message' => "successfully created position data"
            ]);
        $this->assertDatabaseHas('positions', [
            'name' => 'Database Administrator',
        ]);
    }

    public function testUpdateData(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['position-edit']
        );
        $position = (new Position)->create([
            'name' => 'IT Support',
            'description' => 'description'
        ]);
        $response = $this->putJson('/api/v1/positions/' . $position->id, [
            'name' => 'IT Support',
            'description' => 'description update',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $position->id,
                    'name' => 'IT Support',
                    'description' => 'description update',
                ],
                'message' => "successfully update position data"
            ]);
        $this->assertDatabaseHas('positions', [
            'id' => $position->id,
            'name' => 'IT Support',
            'description' => 'description update',
        ]);
    }

    public function testShowData(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['position-show']
        );
        $position = (new Position)->create([
            'name' => 'IT Developer',
            'description' => 'description'
        ]);
        $response = $this->getJson('/api/v1/positions/' . $position->id . '/show');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $position->id,
                    'name' => $position->name,
                    'description' => $position->description,
                ],
                'message' => "successfully show position data"
            ]);
    }

    public function testDeleteData(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['position-delete']
        );
        $position = (new Position)->create([
            'name' => 'Backend Developer',
            'description' => 'description'
        ]);
        $response = $this->deleteJson('/api/v1/positions/' . $position->id);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [],
                'message' => "successfully delete position data"
            ]);
        $this->assertSoftDeleted($position);
    }
}
