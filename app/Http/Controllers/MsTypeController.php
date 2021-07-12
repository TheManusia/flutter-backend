<?php

namespace App\Http\Controllers;

use App\Models\MsType;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MsTypeController extends Controller
{
    public function show(Request $request)
    {

        $data = MsType::skip($request->start)->take($request->length)->get();

        return $this->response([
            'draw' => (int) $request->draw,
            'start' => (int) $request->start,
            'recordsTotal' => MsType::all()->count(),
            'recordsFiltered' => MsType::all()->count(),
            'data' =>  $data
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
