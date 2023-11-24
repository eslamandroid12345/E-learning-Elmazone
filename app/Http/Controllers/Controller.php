<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public static function returnResponseDataApi($data=null,string $message,int $code,int $status = 200): JsonResponse{

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



    public function saveImageInFolder($filePathLink,$folder): ?string
    {

        if ($filePathLink != null) {

            $file = $filePathLink;
            $folderPath = $folder;
            $link = date('YmdHis') . "." . time().rand(1,4000).$file->getClientOriginalExtension();
            $file->move($folderPath,$link);

            return $link;
        }else{

            return null;
        }
    }


}
