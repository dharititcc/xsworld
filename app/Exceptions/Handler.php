<?php

namespace App\Exceptions;

use ErrorException;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

/**
 * Class Handler.
 */
class Handler extends ExceptionHandler
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        // GeneralException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     *
     * @throws \Throwable
     * @return mixed|void
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @throws \Throwable
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $exception)
    {
        $this->request = $request;

        // Do not send exception report if the exception is of type GeneralException
        if ($this->shouldReport($exception) && !($exception instanceof GeneralException)) {
            // $this->sendExceptionEmail($exception);
        }

        if (strpos($request->url(), '/api/') !== false || $request->header('Accepts') == 'application/json' || $request->ajax()) {

            // Log::debug('API Request Exception - '.$request->url().' - '.$exception->getMessage().(! empty($request->all()) ? ' - '.json_encode($request->except(['password'])) : ''));

            if ($exception instanceof AuthorizationException) {
                return $this->setStatusCode(403)->respondWithError($exception->getMessage());
            }

            if ($exception instanceof MethodNotAllowedHttpException) {
                return $this->setStatusCode(403)->respondWithError('Please check HTTP Request Method. - MethodNotAllowedHttpException');
            }

            if ($exception instanceof NotFoundHttpException) {
                return $this->setStatusCode(403)->respondWithError('Please check your URL to make sure request is formatted properly. - NotFoundHttpException');
            }

            if ($exception instanceof GeneralException) {
                return $this->setStatusCode((!$exception->getCode()) ? 403 : $exception->getCode())->respondWithError($exception->getMessage());
            }

            if ($exception instanceof TokenMismatchException) {
                return $this->setStatusCode(302)->respondWithError($exception->getMessage());
            }

            if ($exception instanceof ErrorException) {
                return $this->setStatusCode(500)->respondWithError("Internal Server Error, Please Try again.");
            }

            if ($exception instanceof ModelNotFoundException) {
                return $this->setStatusCode(404)->respondWithError('Item could not be found. Please check identifier.');
            }

            if ($exception instanceof AuthenticationException) {
                return $this->setStatusCode(401)->respondWithError('Unauthenticated.');
            }

            if ($exception instanceof ValidationException) {
                // Log::debug('API Validation Exception - '.json_encode($exception->validator->messages()));

                if(strpos($request->url(), '/api/') !== false) {
                    return $this->setStatusCode(422)->respondWithError($exception->validator->messages()->first());
                }

                return $this->setStatusCode(422)->respondWithError($exception->validator->messages());
            }

            /*
            * Redirect if token mismatch error
            * Usually because user stayed on the same screen too long and their session expired
            */
            if ($exception instanceof UnauthorizedHttpException) {
                switch (get_class($exception->getPrevious())) {
                    case self::class:
                        return $this->setStatusCode($exception->getStatusCode())->respondWithError('Token has not been provided.');
                }
            }
        }

        return parent::render($request, $exception);
    }

    /**
     * get the status code.
     *
     * @return statuscode
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * set the status code.
     *
     * @param [type] $statusCode [description]
     *
     * @return statuscode
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * respond with error.
     *
     * @param $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithError($message)
    {
        if(strpos($this->request->url(), '/api/') !== false) {
            return $this->respond([
                'status'    =>  false,
                'message'   => $message,
            ]);
        }
        
        return $this->respond([
            'error' => [
                'message' => $message,
                'status_code' => $this->getStatusCode(),
            ],
        ]);
    }

    /**
     * Respond.
     *
     * @param array $data
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respond($data, $headers = [])
    {
        return response()->json($data, $this->getStatusCode(), $headers);
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $request->expectsJson()
                    ? response()->json(['message' => $exception->getMessage()], 401)
                    : redirect()->guest($exception->redirectTo() ?? route('frontend.auth.login'));
    }
}