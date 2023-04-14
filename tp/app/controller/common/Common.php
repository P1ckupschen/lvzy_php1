<?php

namespace app\controller\common;
require_once('app/result/Result.php');
use app\BaseController;
use app\result\Result\Result;
use think\Exception;
use think\facade\Db;
use think\facade\Filesystem;
use think\Request;

class Common extends BaseController
{
    /**
     *                  统一图片上传
     */
    public function uploadpic()
    {
        $uploadedFile = $this->request->file();
        try {
            $savename = \think\facade\Filesystem::disk('public')->putFile('upload', $uploadedFile['file']);
//        $savename = Filesystem::disk()->putFile( '/storage/upload',$uploadedFile['file']);
            $result = '/public/storage/' . $savename;
        }catch (Exception $e){
            return Result::Error('10002','图片上传失败请重试');
        }
//       return Result::Success($result);
        //返回地址 前端拼接
        return json($result);
    }
    public function deletePic(){
        $path = $this->request->param();
        dump($path['path']);
        unlink('.'.$path['path']);
    }
    public function getProductByPage(){
//        $pageNum = $this->request->get('pageNum');
//        $pageSize = $this->request->get('pageSize');
//        $categoryId = $this->request->get('categoryid');
////        dump($categoryId);exit();
//        $list = Db::table('sys_product')->where('category_id',$categoryId)->json(['prd_pic','prd_effectpic'])->paginate(['list_rows'=> $pageSize, 'page' => $pageNum]);
//        if ($list!=null) {
//            foreach ($list as $key=>$item) {
//                if($item['prd_pic']!==null) {
//                    $item['prd_pic'] = array_map(function ($data){
//                        $data['url'] = config('app.app_host'). str_replace("//","/",str_replace("\\","/",$data['url']));
////                        $data['url'] = 'https://phpssl.hangzhouwan.net/lvzy/'. str_replace("//","/",str_replace("\\","/",$data['url']));
//                        return $data;
//                    }, $item['prd_pic']);
//                }
//                $list[$key] = $item;
//            }
//        }

//        return json($list);
        $categoryId = $this->request->get('categoryid');
        $info = Db::table('sys_category')->where('category_id',$categoryId)->json(['category_pic'])->find();

        if($info['category_pic']!==null){
            foreach ($info['category_pic'] as $key=>$item) {
                if ($item['url'] !== null) {
                    $item['url'] = config('app.app_host') . str_replace("//", "/", str_replace("\\", "/", $item['url']));
                }
                $info['category_pic'][$key] = $item;
            }
        }
        return json($info['category_pic']);

    }
    public function getProductListInMobile(){
//        dump('sdsd');
        $id = $this->request->get('categoryid');
        $List = Db::table('sys_product')->json(['prd_pic'])->where('prd_istopping', 1)->where('category_id', $id)->field('prd_id,prd_pic')->limit(6)->select();
        foreach ($List as $key=>$item) {
            if ($item['prd_pic'] !== null) {
                $item['prd_pic'] = config('app.app_host') . str_replace("//", "/", str_replace("\\", "/", $item['prd_pic'][0]['url']));
            }
            $List[$key] = $item;
        }
        return json($List);
    }


}
