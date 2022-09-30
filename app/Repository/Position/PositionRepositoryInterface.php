<?php

namespace App\Repository\Position;

use App\Models\Position;
use Illuminate\Database\Eloquent\Collection;

interface PositionRepositoryInterface
{
    public function all(): Position;

    public function allWithFilter(): Collection;

    public function getById(int $id): Position;

    public function create(array $position): Position;

    public function update(int $id, array $position): Position;

    public function delete(int $id):Position;
}
