<?php
//全局bootstrap事件
date_default_timezone_set('Asia/Shanghai');
EasySwoole\Component\Di::getInstance()->set(EasySwoole\EasySwoole\SysConst::HTTP_CONTROLLER_MAX_DEPTH,5);
EasySwoole\Component\Di::getInstance()->set(EasySwoole\EasySwoole\SysConst::HTTP_CONTROLLER_NAMESPACE,'App\\Api\\Controller\\');


defined('SUCCESS') or define('SUCCESS',200);
defined('ERROR') or define('ERROR',400);

defined('CHANNEL_TAGE') or define('CHANNEL_TAGE','v2');

defined('USER_SET') or define('USER_SET','user_set:');

defined('VK_PROVINCE') or define('VK_PROVINCE',[
    '广东'  =>  1,  '浙江'  =>  2,  '江苏'  =>  3,  '四川'  =>  4,  '福建'  =>  5,  '山东'  =>  6,  '上海'  =>  7,  '北京'  =>  8,   '湖北'  =>  9, '河北'  =>  10, 
    '河南'  =>  11, '湖南'  =>  12, '辽宁'  =>  13, '江西'  =>  14, '安徽'  =>  15, '重庆'  =>  16, '广西'  =>  17, '黑龙江' =>  18, '贵州'  =>  19, '陕西'  =>  20, 
    '山西'  =>  21, '天津'  =>  22, '新疆'  =>  23, '云南'  =>  24, '吉林'  =>  25, '内蒙古' =>  26, '海南'  => 27, '甘肃'   =>  28, '宁夏'  =>  29, '香港'  =>  30, 
    '青海'  =>  31, '西藏'  =>  32, '澳门'  =>  33, '台湾'  =>  34,'未知'  =>  35,
]);

defined('KV_PROVINCE') or define('KV_PROVINCE',[
    1  => '广东',2  => '浙江',3  => '江苏',4  => '四川',5  => '福建',6  => '山东',7    => '上海',8  => '北京',9    => '湖北',10 => '河北',
    11 => '河南',12 => '湖南',13 => '辽宁',14 => '江西',15 => '安徽',16 => '重庆',17   => '广西',18 => '黑龙江',19 => '贵州',20 => '陕西',
    21 => '山西',22 => '天津',23 => '新疆',24 => '云南',25 => '吉林',26 => '内蒙古',27 => '海南',28 => '甘肃',29   => '宁夏',30 => '香港',
    31 => '青海',32 => '西藏',33 => '澳门',34 => '台湾',35 => '芸芸众生',
]);

function getMsectime()
{ 
    list($msec, $sec) = explode(' ', microtime());
    $msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
    return $msectime;
}

//保持位数即可
function numToStr($num)
{
    if (stripos($num,'e') === false) return $num;
    $num = trim(preg_replace('/[=\'"+]/','',$num,1),'"');
    list($string,$len) = explode('e',$num);
    return bcmul($string,bcpow('10',$len));
}

function encrypt(string $data,string $key,string $iv):string
{
    return base64_encode(openssl_encrypt($data, 'AES-128-CBC', $key, 1, $iv));
}

function decrypt(string $data,string $key,string $iv):string
{
    return openssl_decrypt(base64_decode($data), 'AES-128-CBC', $key, 1, $iv);
}