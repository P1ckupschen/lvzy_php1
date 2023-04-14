<?php

namespace app\common\exception;

use think\exception\Handle;
use think\Response;
use Throwable;

class MyExceptionHandler extends Handle
{
    public $msg;
    public $errorCode;

    public function render($request, Throwable $e): Response
    {
        if ($e instanceof MyException) {
            $this->msg = $e->msg;
            $this->errorCode = $e->errorCode;
        } else {
            return parent::render($request, $e);
        }

        return json(["msg" => $this->msg, "code" => $this->errorCode]);
    }
}