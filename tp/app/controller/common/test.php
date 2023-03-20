<?php

namespace app\controller\common;

use app\BaseController;
use think\facade\Db;
use think\facade\View;
/**
 * 中文切换怎么实现？
 * 如果请求携带参数
 * if 判断
*/

class test extends BaseController
{
    public function list(){
        cookie('lang','en');
        //加上限制数量       类型数据                     TODO
        $categoryList = Db::table('sys_category')->select();
        if($this->request->cookie('lang') == 'en' && $categoryList !==null){
            foreach ($categoryList as $key=>$category){
                $category['category_name']=$category['category_engname'];
                $categoryList[$key] = $category;
            }
            View::assign('categoryList',$categoryList);
        }else{
            View::assign('categoryList',$categoryList);
        }

        //当前类型号的对应产品
        $categoryId = $this->request->get('categoryid');
        if($categoryId ==null){
            $categoryId=$categoryList[0]['category_id'];
        }
        $products = Db::table('sys_product')->where('category_id', $categoryId)->json(['prd_pic'])->field('prd_pic')->limit(5)->select();
        View::assign('products',$products);

        //展会信息    日期前三
        $exhibitionList = Db::table('sys_exhibition')->json(['exhibition_phaseone'])->order('created_time desc')->limit(3)->select()
            ->each(function ($item){
            if($item['exhibition_phaseone'] !==null){
                $item['exhibition_phaseone'] = $item['exhibition_phaseone'][0].'-'.$item['exhibition_phaseone'][1];
            }
            if($item['exhibition_phasetwo'] !==null){
                $item['exhibition_phasetwo'] = $item['exhibition_phasetwo'][0].'-'.$item['exhibition_phasetwo'][1];
            }
            return $item;
        });
        //日期变更
        if($this->request->cookie('lang') == 'en' && $exhibitionList !==null){
            foreach ($exhibitionList as $key=>$exhibition){
                $exhibition['exhibition_title']=$exhibition['exhibition_engtitle'];
                //日期改英文
                $exhibition['exhibition_phaseone']=str_replace('月','/',$exhibition['exhibition_phaseone']);
                $exhibition['exhibition_phaseone']=str_replace('日','',$exhibition['exhibition_phaseone']);
                $exhibition['exhibition_phasetwo']=str_replace('月','/',$exhibition['exhibition_phasetwo']);
                $exhibition['exhibition_phasetwo']=str_replace('日','',$exhibition['exhibition_phasetwo']);
                $exhibitionList[$key] = $exhibition;
            }
            View::assign('exhibitionList',$exhibitionList);
        }else{
            View::assign('exhibitionList',$exhibitionList);
        }

        return View::fetch();
    }

    public function product(){
        //查询类型数据 只包含 （id 中文名称）
        $categoryList = Db::table('sys_category')->field('category_id,category_name,category_engname')->select();
        if($this->request->cookie('lang') == 'en' && $categoryList!==null){
            foreach ($categoryList as $key=>$category){
                $category['category_name']=$category['category_engname'];
                $categoryList[$key] = $category;
            }
            View::assign('categoryList',$categoryList);
        }else{
            View::assign('categoryList',$categoryList);
        }



        //根据类型id 查询页面需要的产品数据  只包含（id 型号 图片）            TODO 处理图片数据显示 并且有分页数据
        $this->request->cookie();
        $categoryId = $this->request->get('categoryid');
        //默认显示第一类
        if($categoryId ==null){
            $categoryId=$categoryList[0]['category_id'];
        }
        $prdList = Db::table('sys_product')->where('category_id',$categoryId)->json(['prd_pic'])->field('prd_id,prd_modelname,prd_pic')->select();
        View::assign('prdList',$prdList);
        //跳转后的产品页面 根据点击的产品id需要 （型号 描述 包装 外箱只数 尺寸 产品效果图 产品图）
        $prdId = $this->request->get('prdid');

        $product = Db::table('sys_product')->where('prd_id', $prdId)->json(['prd_pic','prd_effectpic'])->find();
        //                                                      TODO 图片处理
        if($product!==null){
            $product['prd_description'] = explode('，',$product['prd_description']);
            $product['prd_engdescription'] = explode('，',$product['prd_engdescription']);
        }

        if($this->request->cookie('lang') == 'en' && $product !==null){
            $product['prd_packaging']=$product['prd_engpackaging'];
            $product['prd_description']=$product['prd_engdescription'];
            View::assign('productItem',$product);
            View::assign('productDescription',$product['prd_description']);
        }else{
        View::assign('productItem',$product);
        View::assign('productDescription',$product['prd_description']);
        }
        //跳转后的产品的喷水效果图array
        View::assign('productEffectPic',$product['prd_effectpic']);
        return View::fetch();
    }

    public function exhibition(){
        //展览会页面                                                    TODO  图片处理
        $pageNum = $this->request->get('pageNum');
        $exhibitionList = Db::table('sys_exhibition')->json(['exhibition_pic','exhibition_phaseone','exhibition_phasetwo'])->paginate(['list_rows'=> 3, 'page' =>$pageNum ])
            ->each(function ($item){
               if($item['exhibition_phaseone'] !==null){
                   $item['exhibition_phaseone'] = $item['exhibition_phaseone'][0].'-'.$item['exhibition_phaseone'][1];
               }
               if($item['exhibition_phasetwo'] !==null){
                   $item['exhibition_phasetwo'] = $item['exhibition_phasetwo'][0].'-'.$item['exhibition_phasetwo'][1];
               }
                return $item;
            });
        if($this->request->cookie('lang') == 'en' && $exhibitionList !== null){
            foreach ($exhibitionList as $key=>$exhibition){
                $exhibition['exhibition_title']=$exhibition['exhibition_engtitle'];
                //日期改英文
                $exhibition['exhibition_phaseone']=str_replace('月','/',$exhibition['exhibition_phaseone']);
                $exhibition['exhibition_phaseone']=str_replace('日','',$exhibition['exhibition_phaseone']);
                $exhibition['exhibition_phasetwo']=str_replace('月','/',$exhibition['exhibition_phasetwo']);
                $exhibition['exhibition_phasetwo']=str_replace('日','',$exhibition['exhibition_phasetwo']);
                $exhibitionList[$key] = $exhibition;
            }
            View::assign('exhibitionList',$exhibitionList);
        }else{
            View::assign('exhibitionList',$exhibitionList);
        }


        return View::fetch();
    }

}
