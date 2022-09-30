<?php

namespace App\Http\Controllers;

use App\Http\Requests\Position\CreateRequest;
use App\Http\Requests\Position\UpdateRequest;
use App\Services\Position\PositionServiceInterface;
use App\Traits\RespondsWithHttpStatus;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Log;
use Spatie\QueryBuilder\Exceptions\InvalidSortQuery;
use Throwable;

class PositionController extends Controller
{
    use RespondsWithHttpStatus;

    private PositionServiceInterface $positionService;

    public function __construct(PositionServiceInterface $positionService)
    {
        $this->positionService = $positionService;
    }

    public function index(): Response|Application|ResponseFactory
    {
        try {
            $position = $this->positionService->all();
            return $this->success('data all position', $position);
        } catch (InvalidSortQuery $exception) {
            return $this->failure($exception->getMessage(), 400);
        } catch (Throwable $exception) {
            Log::error('position index : ' . $exception->getMessage());
            return $this->failure('error get department', 400);
        }

    }

    public function store(CreateRequest $request): Response|Application|ResponseFactory
    {
        $validatedData = $request->safe()->only([
            'name',
            'description'
        ]);
        try {
            $position = $this->positionService->create($validatedData);
            return $this->success('successfully created position data', $position);
        } catch (Throwable $exception) {
            Log::error('position create : ' . $exception->getMessage());
            return $this->failure('error create position', 400);
        }
    }

    public function show($id): Response|Application|ResponseFactory
    {
        try {
            $position = $this->positionService->getById($id);
            return $this->success('successfully show position data', $position);
        } catch (ModelNotFoundException $exception) {
            return $this->failure($exception->getMessage(), 404);
        } catch (Throwable $exception) {
            Log::error('position show : ' . $exception->getMessage());
            return $this->failure('error show position', 400);
        }
    }

    public function update(UpdateRequest $request, $id): Response|Application|ResponseFactory
    {
        $validatedData = $request->safe()->only([
            'name',
            'description'
        ]);
        try {
            $department = $this->positionService->update($id, $validatedData);
            return $this->success('successfully update position data', $department);
        } catch (Throwable $exception) {
            Log::error('position update : ' . $exception->getMessage());
            return $this->failure('error update position', 400);
        }
    }

    public function destroy($id): Response|Application|ResponseFactory
    {
        try {
            $this->positionService->delete($id);
            return $this->success('successfully delete position data');
        } catch (ModelNotFoundException $exception) {
            return $this->failure($exception->getMessage(), 404);
        } catch (Throwable $exception) {
            Log::error('position delete : ' . $exception->getMessage());
            return $this->failure('error delete position', 400);
        }
    }
}
