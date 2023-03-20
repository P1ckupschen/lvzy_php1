<?php

namespace app\middleware;

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
        if ($request->cookie('lang') == 'en') {
            //处理逻辑
            return redirect('/list');
            //跳转处理？ 当前处理
        }
        return $next($request);
    }
}
