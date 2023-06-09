<?php
namespace app\controller;
use app\BaseController;
use app\controller\common\test;
use common\helps\utils;
use common\utools;
use think\facade\Db;
use think\facade\Lang;
use think\facade\View;

class Website extends BaseController
{
    public function home()
    {
        //拿到首页顶部banner图
        $urlList = Db::table('sys_rotation')->field('pic_url')->json(['pic_url'])->select();
        foreach ($urlList as $key=>$item){
            if($item['pic_url']!==null && $item['pic_url'] !== [] &&$item['pic_url'][0]['url']!==null  ){
                $item['pic_url'] = $item['pic_url'][0]['url'];
                $item['pic_url'] = str_replace('\\','/',$item['pic_url']);
            }else{
                $item['pic_url'] = null;
            }
            $urlList[$key] = $item;
        }
//        dump($urlList);
        View::assign('urlList',$urlList);


        //文章内容
        $dashboardArticle = Db::table('sys_article')->where('article_id',2)->find();
        //处理 * 号
        $dashboardArticle['article_content'] = explode('*',$dashboardArticle['article_content']);
        $dashboardArticle['article_engcontent'] = explode('*',$dashboardArticle['article_engcontent']);
        $dashboardArticle['article_content'] =array_chunk($dashboardArticle['article_content'],2)[0];
        $dashboardArticle['article_engcontent'] =array_chunk($dashboardArticle['article_engcontent'],2)[0];
        if( Lang::getLangSet() == 'en-us'&& $dashboardArticle !==null){
            $dashboardArticle['article_content']=$dashboardArticle['article_engcontent'];
            View::assign('dashboardArticle',$dashboardArticle['article_content']);
        }else{
            View::assign('dashboardArticle',$dashboardArticle['article_content']);
        }

        //手机版类型数据
        $categoryListmobile = Db::table('sys_category')->limit(10)->select();
        if( Lang::getLangSet() == 'en-us'&& $categoryListmobile !==null){
            foreach ($categoryListmobile as $key=>$category){
                $category['category_name']=$category['category_engname'];
                $categoryListmobile[$key] = $category;
            }
            View::assign('categoryListmobile',$categoryListmobile);
        }else{
            View::assign('categoryListmobile',$categoryListmobile);
        }
        $categoryList = Db::table('sys_category')->where('category_istopping',1)->limit(10)->select();
        if( Lang::getLangSet() == 'en-us'&& $categoryList !==null){
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
        View::assign('categoryId',$categoryId);

        //手机版 6个推荐产品图 prd_istopping为1
        $prdListMobile = Db::table('sys_product')->json(['prd_pic'])->where('category_id',$categoryId)->where('prd_istopping',1)->field('prd_id,prd_pic')->limit(6)->select();
//        dump($prdListMobile);
        foreach ($prdListMobile as $key=>$item) {
            if ($item['prd_pic'] !== null) {
                $item['prd_pic'] = config('app.app_host') . str_replace("//", "/", str_replace("\\", "/", $item['prd_pic'][0]['url']));
            }
            $prdListMobile[$key] = $item;
        }
//        dump($prdListMobile);
        View::assign('prdListMobile',$prdListMobile);
        //新的是读取类型中的推荐产品图
        $info = Db::table('sys_category')->where('category_id',$categoryId)->json(['category_pic'])->find();
        if($info['category_pic']!==null){
            foreach ($info['category_pic'] as $key=>$item) {
                if ($item['url'] !== null) {
                    $item['url'] = config('app.app_host') . str_replace("//", "/", str_replace("\\", "/", $item['url']));
                }
                $info['category_pic'][$key] = $item;
            }
        }
        View::assign('categorypic',$info['category_pic']);
        //查询前5个产品
//        $products = Db::table('sys_product')->where('category_id', $categoryId)->json(['prd_pic'])->field('prd_pic')->limit(5)->select();
////        dump($categoryId);
//        if ($products!=null) {
//            $products = $products->toArray();
//            foreach ($products as $key=>$item) {
//                if($item['prd_pic']!==null) {
//                    $item['prd_pic'] = array_map(function ($data){
//                        $data['url'] = config('app.app_host'). str_replace("//","/",str_replace("\\","/",$data['url']));
//                        return $data;
//                    }, $item['prd_pic']);
//                }
//                $products[$key] = $item;
//            }
//        }
//        View::assign('products',$products);

        //展会信息    日期前四
        $day = array();
        $exhibitionList = Db::table('sys_exhibition')->json(['exhibition_phaseone','exhibition_pic'])->order('created_time desc')->limit(4)->select()
            ->each(function ($item){
                if($item['exhibition_phaseone'] !==null){
                    $item['exhibition_phaseone'] = $item['exhibition_phaseone'][0].'-'.$item['exhibition_phaseone'][1];
                }
                if($item['exhibition_phasetwo'] !==null){
                    $item['exhibition_phasetwo'] = $item['exhibition_phasetwo'][0].'-'.$item['exhibition_phasetwo'][1];
                }
                if($item['exhibition_pic']!==null) {
                    $item['exhibition_pic'] = array_map(function ($data){
                        $data['url'] = config('app.app_host') . str_replace("//","/",str_replace("\\","/",$data['url']));
                        return $data;
                    }, $item['exhibition_pic']);
                }
                return $item;
            });
        foreach ($exhibitionList as $key=>$item){
            if($item['created_time']!==null){
                $last = substr($item['created_time'], 8, 10);
                $item['created_time'] = substr($item['created_time'],0,7);
                array_push($day,$last);
            }else{
                array_push($day,'');
            }
            $exhibitionList[$key] =$item;
        }
        //日期变更


        if(Lang::getLangSet()=='en-us' && $exhibitionList !==null){
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
        View::assign('day',$day);
        if($this->request->isMobile()){
            return View::fetch('home_mobile');
        }
        return View::fetch();
    }
    
    public function about()
    {
        //文章内容
        $aboutArticle = Db::table('sys_article')->where('article_id', 2)->find();
        //处理 * 号 explode 根据给的符号 分割成array
        $aboutArticle['article_content'] = explode('*', $aboutArticle['article_content']);
        $aboutArticle['article_engcontent'] = explode('*', $aboutArticle['article_engcontent']);
        if (Lang::getLangSet() == 'en-us' && $aboutArticle !== null) {
            $aboutArticle['article_content'] = $aboutArticle['article_engcontent'];
            View::assign('aboutArticle', $aboutArticle['article_content']);
        } else {
            View::assign('aboutArticle', $aboutArticle['article_content']);
        }


        $categoryList = Db::table('sys_category')->select();
        if (Lang::getLangSet() == 'en-us' && $categoryList !== null) {
            foreach ($categoryList as $key => $category) {
                $category['category_name'] = $category['category_engname'];
                $categoryList[$key] = $category;
            }
            View::assign('categoryList', $categoryList);
        } else {
            View::assign('categoryList', $categoryList);
        }
        $equipmentPic = Db::table('sys_equipment')->where('equipment_id', 1)->json(['equipment_pic'])->field('equipment_pic')->find();
        if ($equipmentPic['equipment_pic'] != null) {
            foreach ($equipmentPic['equipment_pic'] as $key => $item) {
                if ($item !== null) {
                    $item['url'] = config('app.app_host') . str_replace("//", "/", str_replace("\\", "/", $item['url']));
                    $equipmentPic['equipment_pic'][$key] = $item;
                }
            }
            View::assign('equipmentPicList', $equipmentPic['equipment_pic']);

            /**
             * 如果是移动端 则进行模板跳转
             */

        }
        if($this->request->isMobile()){
            return View::fetch('about_mobile');
        }
            return View::fetch();

    }
    
    public function product() {
        //查询类型数据 只包含 （id 中文名称）
        $categoryList = Db::table('sys_category')->field('category_id,category_name,category_engname')->select();

        if(Lang::getLangSet() == 'en-us' && $categoryList!==null){
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
        if(Lang::getLangSet() == 'en-us' ){
            $prdListMobile = Db::table('sys_product')->where('category_id',$categoryId)->where('prd_display','in','2,3')->json(['prd_pic'])->field('prd_id,prd_pic')->select();
        }else{
            $prdListMobile = Db::table('sys_product')->where('category_id',$categoryId)->where('prd_display','in','1,3')->json(['prd_pic'])->field('prd_id,prd_pic')->select();
        }
        if ($prdListMobile!=null) {
            foreach ($prdListMobile as $key=>$item) {
                if($item['prd_pic']!==null) {
                    $item['prd_pic'] = array_map(function ($data){
                        $data['url'] = config('app.app_host'). str_replace("//","/",str_replace("\\","/",$data['url']));
                        return $data;
                    }, $item['prd_pic']);
                }
                $prdListMobile[$key] = $item;
            }
        }
        View::assign('prdListmobile',$prdListMobile);
        $pageNum = $this->request->get('pagenum',1);
        $pageSize = $this->request->get('pagesize',12);
        if(Lang::getLangSet() == 'en-us' ){
            $prdList = Db::table('sys_product')->where('category_id',$categoryId)->where('prd_display','in','2,3')->json(['prd_pic'])->field('prd_id,prd_modelname,prd_pic')->paginate(['list_rows'=> $pageSize, 'page' =>$pageNum ]);
        }else{
            $prdList = Db::table('sys_product')->where('category_id',$categoryId)->where('prd_display','in','1,3')->json(['prd_pic'])->field('prd_id,prd_modelname,prd_pic')->paginate(['list_rows'=> $pageSize, 'page' =>$pageNum ]);
        }
//        $prdList = Db::table('sys_product')->where('category_id',$categoryId)->json(['prd_pic'])->field('prd_id,prd_modelname,prd_pic')->paginate(['list_rows'=> $pageSize, 'page' =>$pageNum ]);
//        //产品不为空
        if ($prdList!=null) {
            foreach ($prdList as $key=>$item) {
                if($item['prd_pic']!==null) {
                    $item['prd_pic'] = array_map(function ($data){
                        $data['url'] = config('app.app_host'). str_replace("//","/",str_replace("\\","/",$data['url']));
                        return $data;
                    }, $item['prd_pic']);
                }
                $prdList[$key] = $item;
            }
        }

        View::assign('prdList',$prdList);
        View::assign('total',$prdList->total());
        //跳转后的产品页面 根据点击的产品id需要 （型号 描述 包装 外箱只数 尺寸 产品效果图 产品图）
        if($this->request->isMobile()){
            return View::fetch('product_mobile');
        }
        return View::fetch();
    }
    
    public function product_detail() {
        $categoryList = Db::table('sys_category')->select();
        if( Lang::getLangSet() == 'en-us'&& $categoryList !==null){
            foreach ($categoryList as $key=>$category){
                $category['category_name']=$category['category_engname'];
                $categoryList[$key] = $category;
            }
            View::assign('categoryList',$categoryList);
        }else{
            View::assign('categoryList',$categoryList);
        }
        //跳转后的产品页面 根据点击的产品id需要 （型号 描述 包装 外箱只数 尺寸 产品效果图 产品图）
        $prdId = $this->request->get('prdid');

        $product = Db::table('sys_product')->where('prd_id', $prdId)->json(['prd_pic','prd_effectpic'])->find();
        //                                                      TODO 图片处理
        if ($product!=null) {
            $product['prd_pic'][0]['url']= config('app.app_host'). str_replace("//","/",str_replace("\\","/",$product['prd_pic'][0]['url']));
        }
        if($product!==null){
            $product['prd_description'] = explode('；',$product['prd_description']);
            $product['prd_engdescription'] = explode('；',$product['prd_engdescription']);
        }
        if(Lang::getLangSet() == 'en-us' && $product !==null){
            $product['prd_packaging']=$product['prd_engpackaging'];
            $product['prd_description']=$product['prd_engdescription'];
            View::assign('productItem',$product);
            View::assign('productDescription',$product['prd_description']);
        }else{
            View::assign('productItem',$product);
            View::assign('productDescription',$product['prd_description']);
        }
        if ($product['prd_effectpic']!=null) {
            foreach ($product['prd_effectpic'] as $key=>$item) {
                if($item['url']!==null) {
                    $item['url'] = config('app.app_host'). str_replace("//","/",str_replace("\\","/",$item['url']));
                }
                $product['prd_effectpic'][$key] = $item;
            }
        }

        //跳转后的产品的喷水效果图array
//        if($product['prd_effectpic']==null){
//            View::assign('count',1);
//        }else{
//            View::assign('count',count($product['prd_effectpic'])+1);
//        }
        View::assign('nowlang',Lang::getLangSet());
        View::assign('productEffectPic',$product['prd_effectpic']);

        if($this->request->isMobile()){
            return View::fetch('product_detail_mobile');
        }
        return View::fetch();
    }
    
    public function exhibition() {
        $categoryList = Db::table('sys_category')->select();
        if( Lang::getLangSet() == 'en-us'&& $categoryList !==null){
            foreach ($categoryList as $key=>$category){
                $category['category_name']=$category['category_engname'];
                $categoryList[$key] = $category;
            }
            View::assign('categoryList',$categoryList);
        }else{
            View::assign('categoryList',$categoryList);
        }
        //移动端数据
        $exhibitionList2 = Db::table('sys_exhibition')->json(['exhibition_pic','exhibition_phaseone','exhibition_phasetwo'])->order('created_time desc')->select()
            ->each(function ($item){
                if($item['exhibition_phaseone'] !==null){
                    $item['exhibition_phaseone'] = $item['exhibition_phaseone'][0].'-'.$item['exhibition_phaseone'][1];
                }
                if($item['exhibition_phasetwo'] !==null){
                    $item['exhibition_phasetwo'] = $item['exhibition_phasetwo'][0].'-'.$item['exhibition_phasetwo'][1];
                }
                if($item['exhibition_pic']!==null) {
                    $item['exhibition_pic'] = array_map(function ($data){
                        $data['url'] = config('app.app_host') . str_replace("//","/",str_replace("\\","/",$data['url']));
                        return $data;
                    }, $item['exhibition_pic']);
                }
                return $item;
            });
        if(Lang::getLangSet() == 'en-us' && $exhibitionList2 !== null){
            foreach ($exhibitionList2 as $key=>$exhibition){
                $exhibition['exhibition_title']=$exhibition['exhibition_engtitle'];
                //日期改英文
                $exhibition['exhibition_phaseone']=str_replace('月','/',$exhibition['exhibition_phaseone']);
                $exhibition['exhibition_phaseone']=str_replace('日','',$exhibition['exhibition_phaseone']);
                $exhibition['exhibition_phasetwo']=str_replace('月','/',$exhibition['exhibition_phasetwo']);
                $exhibition['exhibition_phasetwo']=str_replace('日','',$exhibition['exhibition_phasetwo']);
                $exhibitionList2[$key] = $exhibition;
            }
            View::assign('exhibitionList2',$exhibitionList2);
        }else{
            View::assign('exhibitionList2',$exhibitionList2);
        }




        $pageNum = $this->request->get('pagenum',1);
        $pageSize = $this->request->get('pagesize',3);
        $exhibitionList = Db::table('sys_exhibition')->json(['exhibition_pic','exhibition_phaseone','exhibition_phasetwo'])->order('created_time desc')->paginate(['list_rows'=> $pageSize, 'page' =>$pageNum ])
//        $exhibitionList = Db::table('sys_exhibition')->json(['exhibition_pic','exhibition_phaseone','exhibition_phasetwo'])->select()
            ->each(function ($item){
                if($item['exhibition_phaseone'] !==null){
                    $item['exhibition_phaseone'] = $item['exhibition_phaseone'][0].'-'.$item['exhibition_phaseone'][1];
                }
                if($item['exhibition_phasetwo'] !==null){
                    $item['exhibition_phasetwo'] = $item['exhibition_phasetwo'][0].'-'.$item['exhibition_phasetwo'][1];
                }
                if($item['exhibition_pic']!==null) {
                    $item['exhibition_pic'] = array_map(function ($data){
                        $data['url'] = config('app.app_host') . str_replace("//","/",str_replace("\\","/",$data['url']));
                        return $data;
                    }, $item['exhibition_pic']);
                }
                return $item;
            });
        if(Lang::getLangSet() == 'en-us' && $exhibitionList !== null){
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
        View::assign('total',$exhibitionList->total()) ;
        if($this->request->isMobile()){
            return View::fetch('exhibition_mobile');
        }
        return View::fetch();
    }
    
    public function contact() {
        $categoryList = Db::table('sys_category')->select();
        if( Lang::getLangSet() == 'en-us'&& $categoryList !==null){
            foreach ($categoryList as $key=>$category){
                $category['category_name']=$category['category_engname'];
                $categoryList[$key] = $category;
            }
            View::assign('categoryList',$categoryList);
        }else{
            View::assign('categoryList',$categoryList);
        }

        if($this->request->isMobile()){
            return View::fetch('contact_mobile');
        }
        return View::fetch();
    }
}

