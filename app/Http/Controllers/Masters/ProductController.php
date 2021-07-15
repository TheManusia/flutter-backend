<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;
use App\Models\Masters\Product;
use Exception;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    /* @var Product|Relation */
    protected $products;

    public function __construct()
    {
        $this->products = new Product();
    }

    public function show(Request $request)
    {
        try {
            $data = $this->products->withJoin($this->products->defaultSelects);
            return $this->jsonSuccess(null, datatables()->eloquent($data)
                ->with('start', intval($request->start))
                ->toJson()
                ->getOriginalContent()
            );
        } catch (Exception $e) {
            return $this->jsonError($e);
        }
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
