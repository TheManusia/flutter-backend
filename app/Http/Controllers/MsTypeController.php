<?php

namespace App\Http\Controllers;

use App\Models\MsType;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MsTypeController extends Controller
{
    public function show(Request $request)
    {

        return $this->response([
            'draw' => 3,
            'start' => 0,
            'recordsTotal' => 3,
            'recordsFiltered' => 0,
            'data' =>  MsType::all()
        ]);
    }

    public function find($id) {
        return $this->response(MsType::find($id));
    }

    public function store(Request $request) {

        $type = new MsType;

        $type->parentid = $request->parentid;
        $type->typecd = $request->typecd;
        $type->typenm = $request->typenm;
        $type->typeseq = $request->typeseq;
        $type->description = $request->description;

        if ($type->save()) {
            return (new Response('Success', 200));
        }
        return (new Response('Failed', 500));
    }

    private function response($data) {
        return \response()->json([
            'result' => true,
            'status' => 200,
            'message' => 'OK',
            'code' => 200,
            'data' => $data,
        ]);
    }

    public function getResponse($data) {
        return $this->response($data);
    }
}
