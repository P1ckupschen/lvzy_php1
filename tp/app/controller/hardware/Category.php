<?php

namespace app\controller\hardware;
require_once('app/result/Result.php');

use app\BaseController;
use app\result\Result\Result;
use think\Exception;
use think\facade\Db;
use think\Request;

class Category extends BaseController
{
    public function getToppingCount(){
        try {
            $count = Db::table('sys_category')->where('category_istopping', 1)->count();
        }catch (Exception $e){
            return Result::Error('10001','获取列表数据异常请重试');
        }
        return Result::Success('成功');
    }

    /**
     *                      分页  页面获得分类数据
     */
    public function getCategoryListByPage(){
        $pageNum = $this->request->get('pageNum');
        $pageSize = $this->request->get('pageSize');
        try {
            $categoryList= Db::table('sys_category')->json(['category_pic'])->paginate(['list_rows'=> $pageSize, 'page' => $pageNum]);
        }catch (Exception $e){
            return Result::Error('10001','获取列表数据异常请重试');
        }
        return Result::Success($categoryList);
    }
    /**
     *                      页面获得分类数据
     */
    public function getCategoryList(){
        try {
            if($this->request->get('topping')!==null){
                $categoryList= Db::table('sys_category')->where('category_istopping',1)->json(['category_pic'])->select();
            }else{
                $categoryList= Db::table('sys_category')->json(['category_pic'])->select();
            }
        }catch (Exception $e){
            return Result::Error('10001','操作异常请重试');
        }
        return Result::Success($categoryList);
    }


    /**
     *                      删除分类数据
     */
    public function deleteCategoryById(){
        $param = $this->request->param();
        $categoryId = $param['category_id'];
        try {
            Db::table('sys_category')->delete($categoryId);
        }catch (Exception $e){
            return Result::Error('10001','获取列表数据异常请重试');
        }
        return Result::Success('成功');
    }
    /**
     *                      更新分类数据
     */
    public function commitUpdate(){
        $param = $this->request->put();

        try {
            Db::table('sys_category')->json(['category_pic'])->update($param);
        }catch (Exception $e){
            return Result::Error('10001','获取列表数据异常请重试');
        }
        return Result::Success('成功');
    }
    /**
     *                      新增分类数据
     */
    public function commitInsert(){
        $param = $this->request->post();

        try {
            Db::table('sys_category')->json(['category_pic'])->save($param);
        }catch (Exception $e){
            return Result::Error('10001','获取列表数据异常请重试');
        }
        return Result::Success('成功');
    }

}
