<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response as IlluminateResponse;

/**
 * Base API Controller.
 */
class APIController extends Controller
{
    /**
     * default status code.
     *
     * @var int
     */
    protected $statusCode = 200;

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
     * respond with pagincation.
     *
     * @param Paginator $items
     * @param array     $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithPagination($items, $data)
    {
        $data = array_merge($data, [
            'paginator' => [
                'total_count' => $items->count(),
                'total_pages' => ceil($items->count() / $items->perPage()),
                'current_page' => $items->currentPage(),
                'limit' => $items->perPage(),
            ],
        ]);

        return $this->respond($data);
    }

    /**
     * Respond Created.
     *
     * @param string $message
     * @param array|null $item
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondCreated($message, $item=null)
    {
        return $this->setStatusCode(201)->respond([
            'status'    =>  true,
            'message'   =>  $message,
            'item'      => $item ?? null,
        ]);
    }

    /**
     * Respond Success.
     *
     * @param string $message
     * @param array|null $item
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondSuccess($message, $item=null)
    {
        $data = [
            'status'    =>  true,
            'message'   =>  $message,
        ];

        if($item) {
            $data['item'] = $item;
        }

        return $this->respond($data);
    }

    /**
     * respond with error.
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithError($message)
    {
        return $this->respond([
            'status'    =>  false,
            'message'   => $message,
        ]);
    }

    /**
     * responsd not found.
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondNotFound($message = 'Not Found')
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_NOT_FOUND)->respondWithError($message);
    }

    /**
     * Respond with error.
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondInternalError($message = 'Internal Error')
    {
        return $this->setStatusCode(500)->respondWithError($message);
    }

    /**
     * Respond with unauthorized.
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondUnauthorized($message = 'Unauthorized')
    {
        return $this->setStatusCode(401)->respondWithError($message);
    }

    /**
     * Respond with forbidden.
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondForbidden($message = 'Forbidden')
    {
        return $this->setStatusCode(403)->respondWithError($message);
    }

    /**
     * Respond with no content.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithNoContent()
    {
        return $this->setStatusCode(204)->respond(null);
    }

    /**Note this function is same as the below function but instead of responding with error below function returns error json
     * Throw Validation.
     *
     * @param string $message
     *
     * @return mix
     */
    public function throwValidation($message)
    {
        return $this->setStatusCode(422)
            ->respondWithError($message);
    }
}
