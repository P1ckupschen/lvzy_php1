<?php

namespace app\controller\company;

use app\BaseController;
use think\facade\Db;
use think\Request;

class Exhibition extends BaseController
{
    public function getExhibitionList(){
        $pageNum = $this->request->get('pageNum');
        $pageSize = $this->request->get('pageSize');
        $dbs = Db::table('sys_exhibition')->json(['exhibition_pic','exhibition_phaseone','exhibition_phasetwo'])->paginate(['list_rows'=> $pageSize, 'page' =>$pageNum ]);
        return json($dbs);
    }
    public function commitUpdate(){
        $updateInfo =$this->request->put('updateinfo');
        $updateInfo['exhibition_phaseone'] = json_encode($updateInfo['exhibition_phaseone'], JSON_UNESCAPED_UNICODE);
        $updateInfo['exhibition_phasetwo'] = json_encode($updateInfo['exhibition_phasetwo'],JSON_UNESCAPED_UNICODE);
        Db::table('sys_exhibition')->json(['exhibition_pic'])->update($updateInfo);
    }
    public function deleteExhibition(){
        $param = $this->request->param();
        Db::table('sys_exhibition')->where('exhibition_id',$param['exhibition_id'])->delete();
    }
    public function insertExhibition(){
        $insertInfo = $this->request->post('insertinfo');
            $insertInfo['exhibition_phaseone'] = json_encode($insertInfo['exhibition_phaseone'], JSON_UNESCAPED_UNICODE);
            $insertInfo['exhibition_phasetwo'] = json_encode($insertInfo['exhibition_phasetwo'],JSON_UNESCAPED_UNICODE);
        Db::table('sys_exhibition')->json(['exhibition_pic'])->save($insertInfo);
    }

}
