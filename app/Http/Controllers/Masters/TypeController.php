<?php

namespace App\Http\Controllers\Masters;

use App\Constant\DBCode;
use App\Constant\DBMessage;
use App\Http\Controllers\Controller;
use App\Models\Masters\Types;
use Exception;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use function response;

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

            if(is_null($row))
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

    private function response($data, $status, $result, $message)
    {
        return response()->json([
            'result' => $result,
            'status' => $status,
            'message' => $message,
            'code' => $status,
            'data' => $data,
        ]);
    }

    public function select(Request $request)
    {
        $datas = Types::where('typenm', 'like', "%$request->searchValue%");

        if ($request->typeid != '') {
            $datas->where('id', '!=', $request->typeid);
        }
        $result = [];

        foreach (($datas->get()) as $data) {
            array_push($result, [
                'value' => $data->id,
                'text' => $data->typenm
            ]);
        }

        return (new TypeController)->getResponse($result, 200, true, 'OK');
    }

    public static function getResponse($data, $status, $result, $message)
    {
        return (new TypeController())->response($data, $status, $result, $message);
    }

    public function update(Request $request, $id)
    {
        $type = Types::find($id);

        $type = $this->insert($request, $type);

        if ($type->save()) {
            return $this->response("OK", 200, true, 'OK');
        }
        return $this->response("Failed", 500, false, 'Failed to save data');
    }

    private function insert($request, $type)
    {
        $type->parentid = $request->parentid;
        $type->typecd = $request->typecd;
        $type->typenm = $request->typenm;
        $type->typeseq = $request->typeseq;
        $type->description = $request->description;

        return $type;
    }

    public function delete(Request $request, $id)
    {
        $type = Types::find($id);

        if ($type->delete()) {
            return $this->response("OK", 200, true, 'OK');
        }
        return $this->response("Failed", 500, false, 'Failed to delete data');
    }
}
