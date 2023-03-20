<?php

namespace app\controller\hardware;

use app\BaseController;
use think\facade\Db;
use think\Request;

class Category extends BaseController
{
    /**
     *                      分页  页面获得分类数据
     */
    public function getCategoryListByPage(){
        $pageNum = $this->request->get('pageNum');
        $pageSize = $this->request->get('pageSize');
        $categoryList= Db::table('sys_category')->json(['category_pic'])->paginate(['list_rows'=> $pageSize, 'page' => $pageNum]);
        return json($categoryList);
    }
    /**
     *                      页面获得分类数据
     */
    public function getCategoryList(){
        $categoryList= Db::table('sys_category')->json(['category_pic'])->select();
        return json($categoryList);
    }
    /**
     *                      删除分类数据
     */
    public function deleteCategoryById(){
        $param = $this->request->param();
        $categoryId = $param['category_id'];
        Db::table('sys_category')->delete($categoryId);
    }
    /**
     *                      更新分类数据
     */
    public function commitUpdate(){
        $param = $this->request->put();
        Db::table('sys_category')->json(['category_pic'])->update($param);
    }
    /**
     *                      新增分类数据
     */
    public function commitInsert(){
        $param = $this->request->post();
        Db::table('sys_category')->json(['category_pic'])->save($param);
    }

}
