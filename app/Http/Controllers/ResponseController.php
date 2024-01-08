<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResponseController extends Controller
{
    public  static function data($data = [],$status = 200){
        return response($data,$status);
    }
    public static function error($message = null, $status = 404){
        return response([
            'message' => $message,
        ],$status);
    }
    public static function  success($message = null, $status = 200){
        return response([
            'message'=>$message
        ], $status);
    }
}
