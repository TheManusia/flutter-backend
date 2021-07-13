<?php

namespace App\Http\Controllers;

use App\Models\MsProduct;
use Illuminate\Http\Request;

class MsProductController extends Controller
{

    public function show(Request $request)
    {
        $data = MsProduct::with('type')->skip($request->start)->take($request->length)->get();

        return $this->response([
            'draw' => (int)$request->draw,
            'start' => (int)$request->start,
            'recordsTotal' => MsProduct::all()->count(),
            'recordsFiltered' => MsProduct::all()->count(),
            'data' => $data
        ], 200, true, 'OK');
    }

    private function response($data, $status, $result, $message) {
        return MsTypeController::getResponse($data, $status, $result, $message);
    }
}
