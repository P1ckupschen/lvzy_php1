<?php

namespace app\controller\mail;

use app\BaseController;
use think\facade\Db;
use think\Request;

class message extends BaseController
{
   public function getMessageList(){
       $pageNum = $this->request->get('pageNum');
       $pageSize = $this->request->get('pageSize');
       $pageList = Db::table('sys_message')->paginate(['list_rows' => $pageSize, 'page' => $pageNum]);
       return json($pageList);
   }
}
