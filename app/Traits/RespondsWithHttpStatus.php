<?php
namespace App\Traits;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

trait RespondsWithHttpStatus
{
    protected function success($message, $data = [], $status = 200): Response|Application|ResponseFactory
    {
        return response([
            'success' => true,
            'data' => $data,
            'message' => $message,
        ], $status);
    }

    protected function failure($message, $status = 422): Response|Application|ResponseFactory
    {
        return response([
            'success' => false,
            'message' => $message,
        ], $status);
    }

    protected function failureValidation($message,$data, $status = 400): Response|Application|ResponseFactory
    {
        return response([
            'success' => false,
            'data'=>$data,
            'message' => $message,
        ], $status);
    }
}