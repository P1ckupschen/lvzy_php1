<?php
namespace app\controller\admin;
require_once('app/result/Result.php');
use app\BaseController;
use app\model\user;
use thans\jwt\facade\JWTAuth;
use think\facade\Db;


class Index extends BaseController
{
    public function index()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:) </h1><p> ThinkPHP V' . \think\facade\App::version() . '<br/><span style="font-size:30px;">16载初心不改 - 你值得信赖的PHP框架</span></p><span style="font-size:25px;">[ V6.0 版本由 <a href="https://www.yisu.com/" target="yisu">亿速云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="ee9b1aa918103c4fc"></think>';
    }

    public function login(){
        //密码加密
//        dump(password_hash('admin',PASSWORD_BCRYPT));
        //$2y$10$ynNIK/9DAeUUGevvdunlwe7WTjUCJg76W7cbZdt6XtXvKnQxmWe9.
        $password=$this->request->post('password');
        $username=$this->request->post('username');
//        dump($username);exit();
        $ps = Db::table('sys_user')->where('user_name',$username)->value('user_password');
        $id = Db::table('sys_user')->where('user_name',$username)->value('user_id');
        if(password_verify($password,$ps)){
            //登录成功跳转页面 并且生成token 返回
            $token =JWTAuth::builder(['user_id'=>$id]);
            return json(['token'=>$token]);
        }else{
//            dump('用户名或密码错误');
//            返回用户名或密码错误
            return json(['code'=>'201','msg'=>'用户名密码错误请重试']);
        }
    }
    public function userinfo(){
        //根据token拿取用户信息
        $tokenpayload = JWTAuth::getPayload();
        $tokenid =$tokenpayload['user_id']->getValue();
        dump($tokenpayload['user_id']->getValue());
        $user = Db::table('sys_user')->where('user_id',$tokenid)->find();
        $user['user_password'] = 'null';
        return json($user);
    }

}
