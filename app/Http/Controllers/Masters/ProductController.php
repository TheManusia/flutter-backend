<?php

namespace App\Http\Controllers\Masters;

use App\Constant\DBCode;
use App\Constant\DBMessage;
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
        try {
            $this->products->create($request->all());

            return $this->jsonSuccess(DBMessage::SUCCESS_ADD);
        } catch (Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function find($id)
    {
        try {
            $row = $this->products->withJoin($this->products->defaultSelects)
                ->find($id);

            if (is_null($row))
                throw new Exception(DBMessage::ERROR_CORRUPT_DATA, DBCode::AUTHORIZED_ERROR);

            return $this->jsonSuccess(null, $row);
        } catch (Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $row = $this->products->find($id);

            if (is_null($row))
                throw new Exception(DBMessage::ERROR_CORRUPT_DATA, DBCode::AUTHORIZED_ERROR);

            $row->update($request->all());

            return $this->jsonSuccess(DBMessage::SUCCESS_EDIT);
        } catch (Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function delete($id)
    {
        try {

            $row = $this->products->find($id);

            if (is_null($row))
                throw new Exception(DBMessage::ERROR_CORRUPT_DATA, DBCode::AUTHORIZED_ERROR);

            $row->delete();

            return $this->jsonSuccess(DBMessage::SUCCESS_DELETED);
        } catch (Exception $e) {
            return $this->jsonError($e);
        }
    }
}
