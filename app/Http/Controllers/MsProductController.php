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

    public function store(Request $request)
    {
        $product = new MsProduct();

        $product = $this->insert($request, $product);

        if ($product->save()) {
            return $this->response("OK", 200, true, 'OK');
        }
        return $this->response("Failed", 500, false, 'Failed to save data');
    }

    public function select(Request $request)
    {
        $data = MsProduct::where('productnm', 'like', "%$request->searchValue%")->get();

        return MsTypeController::getResponse($data, 200, true, 'OK');
    }

    public function find($id)
    {
        return $this->response(MsProduct::find($id), 200, true, 'OK');
    }

    private function insert($request, $product)
    {
        $product->typeid = $request->typeid;
        $product->productcd = $request->productcd;
        $product->productnm = $request->productnm;
        $product->description = $request->description;

        return $product;
    }

    private function response($data, $status, $result, $message)
    {
        return MsTypeController::getResponse($data, $status, $result, $message);
    }
}
