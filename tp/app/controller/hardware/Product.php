<?php

namespace app\controller\hardware;

use app\BaseController;
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
        $list = Db::table('sys_product')->where('category_id',$categoryId)->json(['prd_pic','prd_effectpic'])->paginate(['list_rows'=> $pageSize, 'page' => $pageNum]);
        return json($list);
    }
    /**
     *                      更新产品数据
     */
    public function updateProduct(){
        $columnDate = $this->request->put('columndata');
        if($columnDate['prd_pic']==[]){
            $columnDate['prd_pic']=null;
        }
        if($columnDate['prd_effectpic']==[]){
            $columnDate['prd_effectpic']=null;
        }
        Db::table('sys_product')->json(['prd_pic'])->json(['prd_pic','prd_effectpic'])->update($columnDate);
    }
    /**
     *                      删除产品数据
     */
    public function deleteProducts(){
        $products = $this->request->param();
        dump($products);
        $ids=[];
        $length = count($products);
        foreach ($products as $item){
            dump($item['prd_id']);
            array_push($ids,$item['prd_id']);
        }
        dump($ids);
        Db::table('sys_product')->where('prd_id','in',$ids)->delete();
    }
    /**
     *                      插入产品数据
     */
    public function insertProductWithPic(){
        $product = $this->request->post('productinfo');
        //把对象中的prd_pic属性里的值都变为json再存放
        if(count($product['prd_pic'])==0){
            $product['prd_pic']=null;
        }
        if(count($product['prd_effectpic'])==0){
            $product['prd_effectpic']=null;
        }
        Db::table('sys_product')->json(['prd_pic','prd_effectpic'])->save($product);
    }
}
