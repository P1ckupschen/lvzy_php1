<?php
namespace app\result\Result;
//namespace app\result;

class Result {
    //success
    static public function Success($data) {
        $rs = [
            'code'=>200,
            'msg'=>"操作成功",
            'data'=>$data,
        ];
        return json($rs);
    }
    //error
    static public function Error($code,$msg) {
        $rs = [
            'code'=>$code,
            'msg'=>$msg,
            'data'=>"",
        ];
        return json($rs);
    }
}