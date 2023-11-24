<?php

namespace App\Http\Repositories;

use Illuminate\Http\JsonResponse;

class ResponseApi{

    public static function returnResponseDataApi($data=null,string $message,int $code,int $status = 200): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ],$status);


    }

    public static function returnResponseDataApiWithMultipleIndexes(array $data,string $message,int $code,int $status = 200): JsonResponse{

        return response()->json([
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ],$status);

    }

}
