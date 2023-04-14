<?php
declare (strict_types = 1);

namespace app\controller\dashboard;
require_once('app/result/Result.php');
use app\BaseController;
use app\result\Result\Result;
use think\Exception;
use think\facade\Db;

class Piclist extends BaseController
{
    public function getPicList(){
        try {
            $list = Db::table('sys_rotation')->json(['pic_url'])->select();
        }catch (Exception $e){
            return Result::Error('10001','获取列表失败请重试');
        }
        return Result::Success($list);
    }
//    ???????????????????
    public function insertPic(){
        $info = $this->request->param();
        try {
            Db::table('sys_rotation')->json(['pic_url'])->insert($info['insertinfo']);
        }catch (Exception $e){
            return Result::Error('10001','插入图片失败请重试');
        }
        return Result::Success('成功');
    }
    public function deletePic(){
        $id = $this->request->param();
        try {
            Db::table('sys_rotation')->delete($id['id']);
        }catch (Exception $e){
            return Result::Error('10001','删除图片失败请重试');
        }
        return Result::Success('成功');

    }
    public function updatePic(){
        $info = $this->request->param();
        try {
            Db::table('sys_rotation')->json(['pic_url'])->update($info['updateinfo']);
        }catch (Exception $e){
            return Result::Error('10001','更新图片失败请重试');
        }
        return Result::Success('成功');
    }
}
