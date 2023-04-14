<?php

namespace app\controller\mail;
require_once('app/result/Result.php');
use app\BaseController;
use app\result\Result\Result;
use think\Exception;
use think\facade\Db;
use think\facade\View;
use think\Request;

class message extends BaseController
{
   public function getMessageList(){
       $pageNum = $this->request->get('pageNum');
       $pageSize = $this->request->get('pageSize');
       try {
           $pageList = Db::table('sys_message')->paginate(['list_rows' => $pageSize, 'page' => $pageNum]);
       }catch (Exception $e){
           return Result::error('10001','获取列表信息失败');
       }
       return Result::Success($pageList);
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
       try {
           Db::table('sys_message')->where('message_id','in',$ids)->delete();
       }catch (Exception $e){
           return Result::Error('10001','删除失败请重试');
       }
       return Result::Success('成功');

   }
}
