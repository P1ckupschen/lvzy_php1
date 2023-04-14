<?php

namespace app\controller\hardware;
require_once('app/result/Result.php');
use app\BaseController;
use app\common\exception\MyException;
use app\result\Result\Result;
use think\Exception;
use think\facade\Db;
use think\Request;

class Equipment extends BaseController
{
    public function getEquipmentInfo(){

        try {
            $dbs = Db::table("sys_equipment")->json(['equipment_pic'])->select();
        }catch (Exception $e){
            return Result::Error('10001','获取列表失败请重试');
        }
        return Result::Success($dbs);
    }
    public function updateEquipment(){
        $putParams = $this->request->put();
        try {
            Db::table("sys_equipment")->json(['equipment_pic'])->save($putParams);
        }catch (Exception $e){
            return Result::Error('10001','更新数据失败请重试');
        }
        return Result::Success('成功');
    }
}
