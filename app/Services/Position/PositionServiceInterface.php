<?php

namespace App\Services\Position;

use App\Models\Position;
use Illuminate\Database\Eloquent\Collection;

interface PositionServiceInterface
{
    public function all(): Collection;

    public function getById(int $id): Position;

    public function create(array $position): Position;

    public function update(int $id,array $position):Position;

    public function delete(int $id): Position;
}
