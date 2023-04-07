<?php

namespace app\middleware;

use http\Cookie;
use think\response\Redirect;

class Checklang
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        if ($request->get('lang') == 'en-us') {
            //处理逻辑
//            cookie('think_lang','en-us');
//            setcookie('think_lang','en-us');
//            return redirect($request->url());
        }elseif(($request->get('lang') == 'zh-cn')){
//            cookie('think_lang','zh-cn');
//            setcookie('think_lang','zh-cn');
        }
        return $next($request);
    }
}
