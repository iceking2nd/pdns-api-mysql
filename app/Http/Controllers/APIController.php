<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class APIController extends Controller
{
    protected $statusCode;

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param mixed $statusCode
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function responseErrorParameters($message = 'Invalid parameters')
    {
        return $this->setStatusCode(422)->responseError($message);
    }

    public function responseWithCreatedData($data)
    {
        return $this->setStatusCode(201)->responseSuccessWithData($data);
    }

    public function responseWithUpdatedData($data)
    {
        return $this->setStatusCode(202)->responseSuccessWithData($data);
    }

    public function responseWithData($data)
    {
        return $this->setStatusCode(200)->responseSuccessWithData($data);
    }

    public function responseSuccessWithData($data)
    {
        return $this->response([
            'code' => $this->getStatusCode(),
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function responseSuccessWithoutData()
    {
        return $this->setStatusCode(204)->response([]);
    }

    public function responseError($message)
    {
        return $this->response([
            'code' => $this->getStatusCode(),
            'status' => 'failed',
            'message' => $message,
        ]);
    }

    public function response($data)
    {
        return response()->json($data,$this->getStatusCode());
    }
}
