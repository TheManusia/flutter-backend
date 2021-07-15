<?php

namespace App\Http\Controllers;

use Exception;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function jsonError(Exception $e, $classname = null, $function = null)
    {

        $code = intval($e->getCode());
        $message = $e->getMessage();

        return response()->json([
            'status' => $code,
            'result' => false,
            'message' => $message,
            'code' => $code,
        ], 200);
    }

    public function jsonSuccess($message, $data = array())
    {
        $json['result'] = true;
        $json['status'] = 200;
        $json['message'] = $message;
        $json['data'] = $data;

        return response()->json($json);
    }
}
