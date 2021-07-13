<?php

namespace App\Http\Controllers;

use App\Models\MsType;
use Illuminate\Http\Request;
use function response;

class MsTypeController extends Controller
{
    public function show(Request $request)
    {

        $data = MsType::skip($request->start)->take($request->length)->get();

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

        $type->parentid = $request->parentid;
        $type->typecd = $request->typecd;
        $type->typenm = $request->typenm;
        $type->typeseq = $request->typeseq;
        $type->description = $request->description;

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
        $data = MsType::where('typenm', 'like', "%$request->searchValue%")->get();

        return (new MsTypeController)->getResponse($data, 200, true, 'OK');
    }

    public function getResponse($data, $status, $result, $message)
    {
        return $this->response($data, $status, $result, $message);
    }
}
