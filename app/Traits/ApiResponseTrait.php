<?php

namespace App\Traits;

trait ApiResponseTrait
{
    protected function success($data, $code)
    {
        return response()->json($data, $code);
    }

    protected function error($message, $code)
    {
        return response()->json(['error' => $message, 'code' => $code, "message" => null], $code);
    }

    protected function showMessage($message, $code = 200)
    {
        return $this->success(['data' => $message], $code);
    }
}
