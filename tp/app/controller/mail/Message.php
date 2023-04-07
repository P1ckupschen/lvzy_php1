<?php

namespace app\controller\mail;

use app\BaseController;
use think\facade\Db;
use think\facade\View;
use think\Request;

class message extends BaseController
{
   public function getMessageList(){
       $pageNum = $this->request->get('pageNum');
       $pageSize = $this->request->get('pageSize');
       $pageList = Db::table('sys_message')->paginate(['list_rows' => $pageSize, 'page' => $pageNum]);
       return json($pageList);
   }
   public function insertMessage(){
       $post = $this->request->post();
       $save = Db::table('sys_message')->save($post);
       if($save!==null){
           return redirect('/index.php/website/contact.html');
       }else{
           return View::fetch();
       }

   }
   public function deleteMessages(){
       $msgs = $this->request->param();
       $ids=[];
       foreach ($msgs as $item){
           array_push($ids,$item['message_id']);
       }
       Db::table('sys_message')->where('message_id','in',$ids)->delete();
   }
}
