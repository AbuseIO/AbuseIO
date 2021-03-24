<?php

namespace AbuseIO\Traits;

use AbuseIO\Api\ErrorCodes;
use AbuseIO\Models\Account;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Response;

/**
 * Class Api.
 */
trait Api
{
    /**
     * @var Account which is used for the api
     */
    protected $api_account = null;

    /**
     * @var int
     */
    protected $statusCode = 200;

    /**
     * @var Manager
     */
    protected $fractal;

    /**
     * @param Manager $fractal
     * @param Request $request
     */
    protected function apiInit(Manager $fractal, Request $request)
    {
        // save the api_account in the controller

        $this->middleware(function ($request, $next) {
            $this->api_account = $request->input('api_account');

            return $next($request);
        });

        $this->fractal = $fractal;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     *
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @param $item
     * @param $callback
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithItem($item, $callback)
    {
        $resource = new Item($item, $callback);

        return $this->respondWithResource($resource);
    }

    /**
     * @param $collection
     * @param $callback
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithCollection($collection, $callback)
    {
        $resource = new Collection($collection, $callback);

        return $this->respondWithResource($resource);
    }

    /**
     * @param array $array
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithArray(array $array, array $headers = [])
    {
        return response()->json($array, $this->statusCode, $headers);
    }

    /**
     * @param $message
     * @param $errorCode
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithError($message, $errorCode)
    {
        if ($this->statusCode === 200) {
            trigger_error(
                'Error with status 200, strange',
                E_USER_WARNING
            );
        }

        return $this->respondWithArray([
            'data'    => [],
            'message' => $this->getMessage($message, $errorCode),
        ]);
    }

    /**
     * @param $messages
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithValidationErrors($messages)
    {
        return $this->setStatusCode(422)
            ->respondWithError($messages, ErrorCodes::CODE_WRONG_ARGS);
    }

    /**
     * @param $message
     * @param $errorCode
     *
     * @return array
     */
    protected function getMessage($message, $errorCode)
    {
        return [
            'code'      => $errorCode,
            'message'   => $message,
            'http_code' => $this->statusCode,
            'success'   => (bool) ($this->statusCode == 200),
        ];
    }

    /**
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorForbidden($message = 'Forbidden')
    {
        return $this->setStatusCode(403)
            ->respondWithError($message, ErrorCodes::CODE_FORBIDDEN);
    }

    /**
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorInternalError($message = 'Internal Error')
    {
        return $this->setStatusCode(500)
            ->respondWithError($message, ErrorCodes::CODE_INTERNAL_ERROR);
    }

    /**
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorNotFound($message = 'Resource Not Found')
    {
        return $this->setStatusCode(404)
            ->respondWithError($message, ErrorCodes::CODE_NOT_FOUND);
    }

    /**
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorUnauthorized($message = 'Unauthorized')
    {
        return $this->setStatusCode(401)
            ->respondWithError($message, ErrorCodes::CODE_UNAUTHORIZED);
    }

    /**
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorWrongArgs($message = 'Wrong Arguments')
    {
        return $this->setStatusCode(400)
            ->respondWithError($message, ErrorCodes::CODE_WRONG_ARGS);
    }

    /**
     * @param $resource
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithResource($resource)
    {
        $rootScope = $this->fractal->createData($resource);
        $data = array_merge(
            $rootScope->toArray(),
            [
                'message' => $this->getMessage('success', ErrorCodes::CODE_SUCCESSFULL),
            ]
        );

        return $this->respondWithArray($data);
    }

    /**
     * @param array $errors
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function response(array $errors)
    {
        if (request()->wantsJson()) {
            return $this->respondWithValidationErrors($errors);
        }

        return $this->redirector->to($this->getRedirectUrl())
            ->withInput($this->except($this->dontFlash))
            ->withErrors($errors);
    }
}
