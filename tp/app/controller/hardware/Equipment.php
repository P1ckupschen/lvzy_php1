<?php

namespace app\controller\hardware;

use app\BaseController;
use think\facade\Db;
use think\Request;

class Equipment extends BaseController
{
    public function getEquipmentInfo(){
        $dbs = Db::table("sys_equipment")->json(['equipment_pic'])->select();
        return json($dbs);
    }
    public function updateEquipment(){
        $putParams = $this->request->put();
        Db::table("sys_equipment")->json(['equipment_pic'])->save($putParams);
    }
}
