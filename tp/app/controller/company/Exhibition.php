<?php

namespace app\controller\company;
require_once('app/result/Result.php');
use app\BaseController;
use app\result\Result\Result;
use think\Exception;
use think\facade\Db;
use think\Request;

class Exhibition extends BaseController
{
    public function getExhibitionList(){
        $pageNum = $this->request->get('pageNum');
        $pageSize = $this->request->get('pageSize');
        try {
            $dbs = Db::table('sys_exhibition')->json(['exhibition_pic','exhibition_phaseone','exhibition_phasetwo'])->paginate(['list_rows'=> $pageSize, 'page' =>$pageNum ]);
        }catch (Exception $e){
            return Result::Error('10001','获取列表信息失败请重试');
        }
        return Result::Success($dbs);
    }
    public function commitUpdate(){
        $updateInfo =$this->request->put('updateinfo');
        try {
            $updateInfo['exhibition_phaseone'] = json_encode($updateInfo['exhibition_phaseone'], JSON_UNESCAPED_UNICODE);
            $updateInfo['exhibition_phasetwo'] = json_encode($updateInfo['exhibition_phasetwo'],JSON_UNESCAPED_UNICODE);
            Db::table('sys_exhibition')->json(['exhibition_pic'])->update($updateInfo);
        }catch (Exception $e){
            return Result::Error('10001','获取列表信息失败请重试');
        }
        return Result::Success('成功');
    }
    public function deleteExhibition(){
        $param = $this->request->param();
        try {
            Db::table('sys_exhibition')->where('exhibition_id',$param['exhibition_id'])->delete();
        }catch (Exception $e){
            return Result::Error('10001','获取列表信息失败请重试');
        }
        return  Result::Success('成功');
    }
    public function insertExhibition(){
        $insertInfo = $this->request->post('insertinfo');
        try {
            $insertInfo['exhibition_phaseone'] = json_encode($insertInfo['exhibition_phaseone'], JSON_UNESCAPED_UNICODE);
            $insertInfo['exhibition_phasetwo'] = json_encode($insertInfo['exhibition_phasetwo'],JSON_UNESCAPED_UNICODE);
            Db::table('sys_exhibition')->json(['exhibition_pic'])->save($insertInfo);
        }catch (Exception $e){
            return Result::Error('10001','获取列表信息失败请重试');
        }
         return Result::Success('成功');

    }

}
