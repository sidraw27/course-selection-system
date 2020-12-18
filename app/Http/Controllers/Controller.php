<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function getResponse(string $msg, int $statusCode = 200, $additional = [])
    {
        $data = ['msg' => $msg];

        if ([] !== $additional) {
            $data = array_merge($data, ['additional' => $additional]);
        }

        return response()->json($data, $statusCode, [], JSON_UNESCAPED_UNICODE);
    }
}
