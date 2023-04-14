<?php

namespace app\common\exception;

use think\Exception;

class MyException extends Exception
{
    public $msg = '异常';
    public $errorCode = 999; // 自定义请求code
    public $code = 200;

    public function __construct($code,$info)
    {
        $this->msg = $info;
        $this->errorCode = $code;
    }

}