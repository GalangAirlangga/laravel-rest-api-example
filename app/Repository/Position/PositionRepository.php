<?php

namespace App\Repository\Position;

use App\Models\Position;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PositionRepository implements PositionRepositoryInterface
{
    protected Position $model;

    public function __construct()
    {
        $this->model=new Position();
    }

    /**
     * get all data position
     * @return Position
     */
    public function all(): Position
    {
        return $this->model;
    }
    /**
     * get all data position
     * with filter name and trashed
     * with sort name and id
     * default sort id DESC
     * @return Collection
     */
    public function allWithFilter(): Collection
    {
        return QueryBuilder::for($this->model)
            ->allowedFilters([
                'name',
                AllowedFilter::trashed(),
            ])
            ->defaultSort('-id')
            ->allowedSorts('name', 'id')
            ->select(['id', 'name', 'description'])
            ->get();
    }

    /**
     * get data position by id
     * @param int $id
     * @return Position
     */
    public function getById(int $id): Position
    {
        $positionData = $this->model::find($id);
        if (!$positionData){
            throw new ModelNotFoundException('data position not found');
        }
        return $positionData;
    }

    /**
     * create data position
     * @param array $position
     * @return Position
     */
    public function create(array $position): Position
    {
       return $this->model::create($position);
    }

    /**
     * update data position
     * @param int $id
     * @param array $position
     * @return Position
     */
    public function update(int $id, array $position): Position
    {
        $positionData = $this->getById($id);
        $positionData->update($position);
        return $positionData;
    }

    /**
     * soft delete data position
     * @param int $id
     * @return Position
     */
    public function delete(int $id): Position
    {
        $positionData = $this->getById($id);
        $positionData->delete();
        return $positionData;
    }
}
