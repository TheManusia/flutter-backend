<?php

namespace App\Http\Controllers;

use App\Models\MsType;
use Illuminate\Http\Request;
use function response;

class MsTypeController extends Controller
{
    public function show(Request $request)
    {

        $data = MsType::with('parent')->skip($request->start)->take($request->length)->get();

        return $this->response([
            'draw' => (int)$request->draw,
            'start' => (int)$request->start,
            'recordsTotal' => MsType::all()->count(),
            'recordsFiltered' => MsType::all()->count(),
            'data' => $data
        ], 200, true, 'OK');
    }

    public function find($id)
    {
        return $this->response(MsType::find($id), 200, true, 'OK');
    }

    public function store(Request $request)
    {
        $type = new MsType;

        $type = $this->insert($request, $type);

        if ($type->save()) {
            return $this->response("OK", 200, true, 'OK');
        }
        return $this->response("Failed", 500, false, 'Failed to save data');
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
        $datas = MsType::where('typenm', 'like', "%$request->searchValue%");

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

        return (new MsTypeController)->getResponse($result, 200, true, 'OK');
    }

    public function getResponse($data, $status, $result, $message)
    {
        return $this->response($data, $status, $result, $message);
    }

    public function update(Request $request, $id) {
        $type = MsType::find($id);

        $type = $this->insert($request, $type);

        if ($type->save()) {
            return $this->response("OK", 200, true, 'OK');
        }
        return $this->response("Failed", 500, false, 'Failed to save data');
    }

    private function insert($request, $type) {
        $type->parentid = $request->parentid;
        $type->typecd = $request->typecd;
        $type->typenm = $request->typenm;
        $type->typeseq = $request->typeseq;
        $type->description = $request->description;

        return $type;
    }
}
