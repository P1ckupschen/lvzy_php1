<?php
// 应用公共文件
use app\common\exception\MyException;

/**
 * 密码加密
 * @param string $password
 * @param string $password_salt
 * @return string
 */
function password($password, $password_salt){
    return md5(md5($password) . md5($password_salt));
}
/**
 * 产生随机字符串
 *
 * @param        int     $length   输出长度
 * @param        string   $chars    可选的 ，默认为 0123456789
 * @return     string  字符串
 */
function random($length, $chars = '0123456789') {
    $hash = '';
    $max = strlen($chars) - 1;
    for($i = 0; $i < $length; $i++) {
        $hash .= $chars[mt_rand(0, $max)];
    }
    return $hash;
}
function test($a)
{
    if ($a == 0) {
        throw new MyException('1001','Division by zero.');
    } else {
        return 10 / $a;
    }
}
