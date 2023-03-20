<?php

namespace app\controller\common;

use app\BaseController;
use think\Request;

class Common extends BaseController
{
    /**
     *                  统一图片上传
     */
    public function uploadpic()
    {
        $uploadedFile = $this->request->file();
        $savename = \think\facade\Filesystem::disk('public')->putFile('upload', $uploadedFile['file']);
        $result = '/storage/' . $savename;
        //返回地址 前端拼接
        return json($result);
    }
}
