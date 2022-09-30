<?php

namespace App\Services\Position;

use App\Models\Position;
use App\Repository\Position\PositionRepositoryInterface;
use DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use InvalidArgumentException;
use Log;
use Spatie\QueryBuilder\Exceptions\InvalidSortQuery;
use Throwable;

class PositionService implements PositionServiceInterface
{
    private PositionRepositoryInterface $positionRepository;

    public function __construct(PositionRepositoryInterface $positionRepository)
    {
        $this->positionRepository = $positionRepository;
    }

    /**
     * this function fot get all data position
     * @return Collection
     * @throws Throwable
     */
    public function all(): Collection
    {
        DB::beginTransaction();
        try {
            $position = $this->positionRepository->allWithFilter();
            DB::commit();
            return $position;
        } catch (InvalidSortQuery $exception) {
            DB::rollBack();
            throw new InvalidSortQuery($exception->unknownSorts, $exception->allowedSorts);
        } catch (Throwable $exception) {
            DB::rollBack();
            Log::error('all position service : ' . $exception->getMessage());
            throw new InvalidArgumentException('Unable to get all position data');
        }
    }

    /**
     * this function for get data position by id
     * @param int $id
     * @return Position
     * @throws Throwable
     */
    public function getById(int $id): Position
    {
        DB::beginTransaction();
        try {
            $position = $this->positionRepository->getById($id);
            DB::commit();
            return $position;
        } catch (Throwable $exception) {
            DB::rollBack();
            Log::error('getById position service : ' . $exception->getMessage());
            throw new InvalidArgumentException('Unable to getById position data');
        }
    }

    /**
     * this function for create data position
     * @param array $position
     * @return Position
     * @throws Throwable
     */
    public function create(array $position): Position
    {
        DB::beginTransaction();
        try {
            $position = $this->positionRepository->create($position);
            DB::commit();
            return $position;
        } catch (Throwable $exception) {
            DB::rollBack();
            Log::error('create position service : ' . $exception->getMessage());
            throw new InvalidArgumentException('Unable to create position');
        }
    }

    /**
     * this function for delete data position
     * @param int $id
     * @param array $position
     * @return Position
     * @throws Throwable
     */
    public function update(int $id, array $position): Position
    {
        DB::beginTransaction();
        try {
            $positionData = $this->positionRepository->update($id, $position);
            DB::commit();
            return $positionData;
        } catch (Throwable $exception) {
            DB::rollBack();
            Log::error('update position service : ' . $exception->getMessage());
            throw new InvalidArgumentException('Unable to update position data');
        }
    }

    /**
     * this function for delete data position by id
     * @param int $id
     * @return Position
     * @throws Throwable
     */
    public function delete(int $id): Position
    {
        Db::beginTransaction();
        try {
            $position = $this->positionRepository->delete($id);
            DB::commit();
            return $position;
        } catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        } catch (Throwable $exception) {
            DB::rollBack();
            Log::error('delete position service : ' . $exception->getMessage());
            throw new InvalidArgumentException('Unable to delete position data');
        }
    }
}
