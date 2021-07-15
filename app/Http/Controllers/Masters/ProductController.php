<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;
use App\Models\Masters\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function show(Request $request)
    {
        $data = Product::with('type')->skip($request->start)->take($request->length)->get();

        return $this->response([
            'draw' => (int)$request->draw,
            'start' => (int)$request->start,
            'recordsTotal' => Product::all()->count(),
            'recordsFiltered' => Product::all()->count(),
            'data' => $data
        ], 200, true, 'OK');
    }

    public function store(Request $request)
    {
        $product = new Product();

        $product = $this->insert($request, $product);

        if ($product->save()) {
            return $this->response("OK", 200, true, 'OK');
        }
        return $this->response("Failed", 500, false, 'Failed to save data');
    }

    public function select(Request $request)
    {
        $data = Product::where('productnm', 'like', "%$request->searchValue%")->get();

        return TypeController::getResponse($data, 200, true, 'OK');
    }

    public function find($id)
    {
        return $this->response(Product::find($id), 200, true, 'OK');
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        $product = $this->insert($request, $product);

        if ($product->save()) {
            return $this->response("OK", 200, true, 'OK');
        }
        return $this->response("Failed", 500, false, 'Failed to save data');
    }

    public function delete(Request $request, $id)
    {
        $product = Product::find($id);

        if ($product->delete()) {
            return $this->response("OK", 200, true, 'OK');
        }
        return $this->response("Failed", 500, false, 'Failed to delete data');
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
        return TypeController::getResponse($data, $status, $result, $message);
    }
}
