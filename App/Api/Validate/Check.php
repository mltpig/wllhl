<?php
namespace App\Api\Validate;
use App\Api\Utils\Jwt;
use EasySwoole\Validate\Validate;
use EasySwoole\Component\CoroutineSingleTon;

class Check
{
    use CoroutineSingleTon;

    public function getValidateData(string $uri,array $param):array
    {

        $class = ClassPath::getInstance()->getPath($uri);

        if(!$class) return ['code' => 1 , 'msg' => $uri." 验证规则设置错误"];
        if(!class_exists($class)) return ['code' => 1 , 'msg' => $uri." 验证类未添加"];

        $rules = $class::getInstance()->getRules();

        if(!$rules) return ['code' => 1 , 'msg' => $uri." 验证规则未设置"];

        $validate = Validate::make($rules);
        if(!$validate->validate($param)) return ['code' => 2 , 'msg' => $validate->getError()->__toString() ] ;
        
        $data = $validate->getVerifiedData();

        if($uri !== 'byteUid' )
        {
            if($uri == 'getUidExtend') $data['playerid'] = base64_decode($data['uid']);
            // if(!$userInfo = Jwt::getInstance()->decode($data['playerid']) ) return ['code' => 2 , 'msg' => '无效的playerid,请重新登录' ] ;
            //$data['playerid'] = $userInfo['uid'];
        }else{
            $data['playerid'] = $data['uid'];
        }

        return $data;
    }

    public function createSign(array $param):string
    {
        ksort($param);
        $str = '';
        foreach ($param as $key => $val) 
        {
            if($key === 'sign' || is_array($val)) continue;
            $str .= $key.'='.$val.'&';
        }

        $secret = $this->getSecretkey($param['timestamp']);

        return strtolower(md5($str.$secret)) ;
    }

    public function getSecretkey(string $timestamp):string
    {
        $string = md5( substr($timestamp,-4) );
        $rand   = [20,7,19,28,10,12,25,2,20,19,10,10,21,6,1,14,31,8,14,18,1,24,12,20,16,20,10,20,16,24,5,30];
        $secret = '';
        foreach ( $rand as $index) 
        {
            $secret .= $string[$index];
        }
        return $secret;
    }

}
