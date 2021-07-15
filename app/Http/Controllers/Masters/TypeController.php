<?php

namespace App\Http\Controllers\Masters;

use App\Constant\DBCode;
use App\Constant\DBMessage;
use App\Http\Controllers\Controller;
use App\Models\Masters\Types;
use Exception;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TypeController extends Controller
{
    /* @var Types|Relation */
    protected $types;

    public function __construct()
    {
        $this->types = new Types();
    }

    public function show(Request $request)
    {
        try {
            $data = $this->types->withJoin($this->types->defaultSelects);
            return $this->jsonSuccess(null, datatables()->eloquent($data)
                ->with('start', intval($request->start))
                ->toJson()
                ->getOriginalContent()
            );
        } catch (Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function find($id)
    {
        try {
            $row = $this->types->withJoin($this->types->defaultSelects)
                ->find($id);

            if (is_null($row))
                throw new Exception(DBMessage::ERROR_CORRUPT_DATA, DBCode::AUTHORIZED_ERROR);

            return $this->jsonSuccess(null, $row);
        } catch (Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function store(Request $request)
    {
        try {
            $this->types->create($request->all());

            return $this->jsonSuccess(DBMessage::SUCCESS_ADD);
        } catch (Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function select(Request $request)
    {
        try {
            $searchValue = trim(strtolower($request->searchValue));
            $query = $this->types->withJoin($this->types->defaultSelects)
                ->where(function ($query) use ($searchValue) {
                    /* @var Relation $query */
                    $query->where(DB::raw('typecd'), 'like', "%$searchValue%");
                    $query->orWhere(DB::raw('typenm'), 'like', "%$searchValue%");
                });

            if ($request->has('typeid') && !empty($request->typeid)) {
                $typeid = $request->post('typeid');
                $query->where('id', '!=', $typeid);
                $query->where('parentid', '!=', $typeid);
            }

            $json = array();
            foreach ($query->get() as $db) {
                $json[] = ['value' => $db->id, 'text' => $db->typenm];
            }

            return $this->jsonSuccess(null, $json);
        } catch (Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function update(Request $request, $id)
    {
        try {

            $row = $this->types->find($id);

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

            $row = $this->types->find($id);

            if (is_null($row))
                throw new Exception(DBMessage::ERROR_CORRUPT_DATA, DBCode::AUTHORIZED_ERROR);

            $row->delete();

            return $this->jsonSuccess(DBMessage::SUCCESS_DELETED);
        } catch (Exception $e) {
            return $this->jsonError($e);
        }
    }
}
