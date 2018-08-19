<?php
/**
 * Created by PhpStorm.
 * User: m1x
 * Date: 016 16.04.18
 * Time: 12:22
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    /**
     * @param $data
     * @param bool $result
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function jsonResponse($data, $result = true, $status = 200)
    {
        $response = [
            'result' => $result,
            'data' => $data,
        ];

        $prettyPrint = request()->get('pretty_print', false);

        return response()->json($response, $status, [], $prettyPrint ? JSON_PRETTY_PRINT : 0);
    }
}