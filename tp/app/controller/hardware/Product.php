<?php

namespace app\controller\hardware;
require_once('app/result/Result.php');
use app\BaseController;

use app\result\Result\Result;
use think\Exception;
use think\facade\Db;
use think\Request;

class Product extends BaseController
{
    /**
     *                      产品分页数据
     */
    public function getProductByPage(){
        $pageNum = $this->request->get('pageNum');
        $pageSize = $this->request->get('pageSize');
        $categoryId = $this->request->get('categoryid');
//        dump($categoryId);exit();
        try {
            $list = Db::table('sys_product')->where('category_id',$categoryId)->json(['prd_pic','prd_effectpic'])->paginate(['list_rows'=> $pageSize, 'page' => $pageNum]);
        }catch (Exception $e){
            return Result::Error('10001','获取产品分页数据错误');
        }
        return Result::Success($list);
    }
    /**
     *                      更新产品数据
     */
    public function updateProduct(){
        $columnDate = $this->request->put('columndata');
//        dump($columnDate);exit();
        if($columnDate['prd_pic']==[]){
            $columnDate['prd_pic']=null;
        }
        if($columnDate['prd_effectpic']==[]){
            $columnDate['prd_effectpic']=null;
        }
        try {
            Db::table('sys_product')->json(['prd_pic','prd_effectpic'])->update($columnDate);
        }catch (Exception $e){
            return  Result::Error('10001','更新数据失败请重试');
        }
        return Result::Success('操作成功');
    }
    /**
     *                      删除产品数据
     */
    public function deleteProducts(){
        $products = $this->request->param();
//        dump($products);
        $ids=[];
        $length = count($products);
        foreach ($products as $item){
//            dump($item['prd_id']);
            array_push($ids,$item['prd_id']);
        }
        try {
            Db::table('sys_product')->where('prd_id','in',$ids)->delete();
        }catch (Exception $e){
             return Result::Error('10001','删除异常请重试');
        }
        return Result::Success('删除成功');

    }
    /**
     *                      插入产品数据
     */
    public function insertProductWithPic(){
        $product = $this->request->post('productinfo');
//        $result = new Result();
//        dump($result);exit();
//        exit();
        //把对象中的prd_pic属性里的值都变为json再存放
        if(count($product['prd_pic'])==0){
            $product['prd_pic']=null;
        }
        if(count($product['prd_effectpic'])==0){
            $product['prd_effectpic']=null;
        }
        try {
            Db::table('sys_product')->json(['prd_pic','prd_effectpic'])->save($product);
        }catch (Exception $e){
            return Result::Error('10001','插入数据异常请重试');
        }
        return Result::Success('操作成功');
    }

    public function getToppingCount(){
        $categoryid = $this->request->get('categoryid');
        try {
            $count = Db::table('sys_product')->json(['prd_pic','prd_effectpic'])->where('prd_istopping', 1)->where('category_id',$categoryid)->count();
        }catch (Exception $e){
            return Result::error('10001','推荐总数查询错误');
        }
        return Result::Success($count);
    }
    public function updateStatus(){
        try {
            $columnDate = $this->request->put();
            Db::table('sys_product')->json(['prd_pic'])->json(['prd_pic','prd_effectpic'])->update($columnDate);
        }catch (Exception $e){
            return Result::Error('10001','更新状态信息错误');
        }
        return Result::Success('更新成功');
    }
}
