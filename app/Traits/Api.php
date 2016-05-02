<?php
namespace AbuseIO\Traits;

use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Manager;
use AbuseIO\Api\ErrorCodes;
use Response;

/**
 * Class Api
 * @package AbuseIO\Traits
 *
 * extends the base class with response methods specific for the api
 */
trait Api
{
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
     * @internal param array $args
     */
    protected function apiInit(Manager $fractal)
    {
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
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithItem($item, $callback)
    {
        $resource = new Item($item, $callback);
        $rootScope = $this->fractal->createData($resource);
        $data = array_merge(
            $rootScope->toArray(),
            [
                'message' => [
                    'code' => ErrorCodes::CODE_SUCCESSFULL,
                    'message' => 'success',
                    'http_code' => $this->statusCode,
                    'success' => $this->statusCode == 200 ? 'true' : 'false'
                ]
            ]);

        return $this->respondWithArray($data);
    }

    /**
     * @param $collection
     * @param $callback
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithCollection($collection, $callback)
    {
        $resource = new Collection($collection, $callback);
        $rootScope = $this->fractal->createData($resource);
        $data = array_merge(
            $rootScope->toArray(),
            [
                'message' => [
                    'code' => ErrorCodes::CODE_SUCCESSFULL,
                    'message' => 'success',
                    'http_code' => $this->statusCode,
                    'success' => $this->statusCode == 200 ? 'true' : 'false'
                ]
            ]);

        return $this->respondWithArray($data);
    }

    /**
     * @param array $array
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    protected  function respondWithArray(array $array, array $headers = [])
    {
        return response()->json($array, $this->statusCode, $headers);
    }

    /**
     * @param $message
     * @param $errorCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithError($message, $errorCode)
    {
        if ($this->statusCode === 200) {
            trigger_error(
                "Error with status 200, strange", E_USER_WARNING
            );
        }

        return $this->respondWithArray([
            'data' => [],
            'message' => [
                'code' => $errorCode,
                'message' => $message,
                'http_code' => $this->statusCode,
                'success' => $this->statusCode == 200 ? 'true' : 'false'
            ]
        ]);
    }

    /**
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorForbidden($message = 'Forbidden')
    {
        return $this->setStatusCode(403)
            ->respondWithError($message, ErrorCodes::CODE_FORBIDDEN);
    }

    /**
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorInternalError($message = 'Internal Error')
    {
        return $this->setStatusCode(500)
            ->respondWithError($message, ErrorCodes::CODE_INTERNAL_ERROR);
    }

    /**
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorNotFound($message = 'Resource Not Found')
    {
        return $this->setStatusCode(404)
            ->respondWithError($message, ErrorCodes::CODE_NOT_FOUND);
    }

    /**
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorUnauthorized($message = 'Unauthorized')
    {
        return $this->setStatusCode(401)
            ->respondWithError($message, ErrorCodes::CODE_UNAUTHORIZED);
    }

    /**
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorWrongArgs($message = 'Wrong Arguments')
    {
        return $this->setStatusCode(400)
            ->respondWithError($message, ErrorCodes::CODE_WRONG_ARGS);
    }
}