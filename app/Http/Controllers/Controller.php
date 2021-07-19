<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{

    public function customValidate(array $data, array $rules, array $messages = array())
    {

        $customRules = array();
        $customAttribtues = array();
        foreach($rules as $attribute => $rule) {
            if(strpos($attribute, ':') !== FALSE) {
                list($attributeRule, $attributeName) = explode(':', $attribute);
                $customRules[$attributeRule] = $rule;
                $customAttribtues[$attributeRule] = $attributeName;
            }

            else {
                $customRules[$attribute] = $rule;
            }
        }

        $validator = Validator::make($data,  $customRules, array_merge([
            'required' => ':attribute tidak boleh kosong',
            'numeric' => ':attribute harus angka'
        ], $messages));

        if($validator->fails()) {
            $strmessage = '';
            foreach($validator->errors()->messages() as $attribtue => $arrmessage) {
                foreach ($arrmessage as $message) {
                    if (array_key_exists($attribtue, $customAttribtues)) {
                        $strmessage .= '- ' . str_replace($attribtue, $customAttribtues[$attribtue], $message) . "\n";
                    }

                    else {
                        $strmessage .= '- ' . str_replace($attribtue, ucfirst($attribtue), $message) . "\n";
                    }
                }
            }

            throw new Exception($strmessage);
        }
    }

    public function jsonError(Exception $e, $classname = null, $function = null)
    {

        $code = intval($e->getCode());
        $message = $e->getMessage();

        return response()->json([
            'status' => $code,
            'result' => false,
            'message' => $message,
            'code' => $code,
        ], 200);
    }

    public function jsonSuccess($message, $data = array())
    {
        $json['result'] = true;
        $json['status'] = 200;
        $json['message'] = $message;
        $json['data'] = $data;

        return response()->json($json);
    }
}
