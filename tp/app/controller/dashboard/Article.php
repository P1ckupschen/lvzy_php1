<?php
declare (strict_types = 1);

namespace app\controller\dashboard;
require_once('app/result/Result.php');
use app\BaseController;
use app\result\Result\Result;
use think\Exception;
use think\facade\Db;
use think\facade\View;
use think\Request;

class Article extends BaseController
{       // /r/n
    public function getArticleInfo(){
        try {
            $articleList = Db::table('sys_article')->select()->each(function ($item){
                $item['article_content']=str_replace('*' ,"\n",$item['article_content']);
                $item['article_engcontent']=str_replace('*' ,"\n",$item['article_engcontent']);
                return $item;
            });
        }catch (Exception $e){
            return Result::Error('10001','获取文章信息失败');
        }

        return Result::Success($articleList);
    }
    public function  updateArticle(){
        $data = $this->request->put('data');
        $data['article_content']=str_replace(array("\n", "\r\n") ,'*',$data['article_content']);
        $data['article_engcontent']=str_replace(array("\n", "\r\n") ,'*',$data['article_engcontent']);
        try {
            Db::table('sys_article')->update($data);
        }catch (Exception $e){
            return Result::Error('10001','更新文章内容失败');
        }
        return Result::Success('成功');
    }

}
