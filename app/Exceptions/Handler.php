<?php

namespace App\Exceptions;

use App\Traits\RespondsWithHttpStatus;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use RespondsWithHttpStatus;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register(): void
    {
        $this->reportable(static function (Throwable $e) {
            //
        });
    }

    /**
     * @param $request
     * @param Throwable $e
     * @return \Illuminate\Http\Response|JsonResponse|RedirectResponse|Application|Response|ResponseFactory
     * @throws Throwable
     */
    public function render($request, Throwable $e): \Illuminate\Http\Response|JsonResponse|RedirectResponse|Application|Response|ResponseFactory
    {
        if ($e instanceof ModelNotFoundException) {
            $modelName = strtolower(class_basename($e->getModel()));

            return $this->failure("Does not exists any $modelName with the specified identification", 404);
        }

        if ($e instanceof AuthenticationException) {
            return $this->unauthenticated($request, $e);
        }

        if ($e instanceof AuthorizationException) {
            return $this->failure($e->getMessage(), 403);
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            return $this->failure('The specified method for the request is invalid', 405);
        }

        if ($e instanceof NotFoundHttpException) {
            return $this->failure('The specified URL cannot be found', 404);
        }

        if ($e instanceof HttpException) {
            return $this->failure($e->getMessage(), $e->getStatusCode());
        }

        if ($e instanceof QueryException) {
            $errorCode = @$e->errorInfo[1];

            if ($errorCode === 1451) {
                return $this->failure('Cannot remove this resource permanently. It is related with any other resource', 409);
            }
        }

        if ($e instanceof TokenMismatchException) {
            return redirect()->back()->withInput($request->input());
        }

        if (config('app.debug')) {
            return parent::render($request, $e);
        }

        return $this->failure('Unexpected Exception. Try later', 500);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param Request $request
     * @param AuthenticationException $exception
     * @return Application|ResponseFactory|\Illuminate\Http\Response|RedirectResponse
     */
    protected function unauthenticated($request, AuthenticationException $exception): \Illuminate\Http\Response|RedirectResponse|Application|ResponseFactory
    {
        if ($this->isFrontend($request)) {
            return redirect()->guest('login');
        }

        return $this->failure('Unauthenticated.', 401);
    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param ValidationException $e
     * @param Request $request
     * @return Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request): Response
    {
        $errors = $e->validator->errors()->getMessages();

        if ($this->isFrontend($request)) {
            return $request->ajax() ? response()->json($errors, 422) : redirect()
                ->back()
                ->withInput($request->input())
                ->withErrors($errors);
        }

        return $this->failure($errors);
    }

    private function isFrontend($request): bool
    {
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }
}
