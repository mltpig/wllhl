<?php

namespace App\Api\Utils;
use EasySwoole\Jwt\Jwt as EasySwooleJwt;
use EasySwoole\EasySwoole\Logger;
use EasySwoole\Component\CoroutineSingleTon;

class Jwt
{
    use CoroutineSingleTon;

    private $ttl       = 86400;
    private $secretKey = 'wgzHCLha2C0Wcu81C89WIPNeaaooXeK4kw95bDj3jO4WlRGuBX9b5NhJFX8jifnzA6HAr8';
    private $aesKey    = 'ZVA0rMCog2uievGy';
    private $aesKIv    = '25T6Ch6Sk1RPaTuN';
  
    public function encode(string $uid):string
    {
        $jwtObject = EasySwooleJwt::getInstance()->setSecretKey($this->secretKey)->publish();
    
        $jwtObject->setAud('xlghz'); // 用户
        $jwtObject->setExp(time()+$this->ttl); // 过期时间
        $jwtObject->setIss('xlghz'); // 发行人
        $data = json_encode([ 'uid'=> $uid ]);
        $jwtObject->setData( encrypt($data,$this->aesKey,$this->aesKIv) );
        
        return $jwtObject->__toString();
    }

    public function decode(string $token):array
    {
        $data = [];
        try {
            $jwtObject = EasySwooleJwt::getInstance()->setSecretKey($this->secretKey)->decode($token);

            switch ($jwtObject->getStatus())
            {
                case  1:
                    $string = decrypt($jwtObject->getProperty('data'),$this->aesKey,$this->aesKIv);
                    if($string)
                    {
                        $result = json_decode($string,true);
                        is_array($result) ?  $data = $result :'';
                    }else{
                        Logger::getInstance()->log('解密错误',0,'Crypt');
                    }
                    break;
                case  -1:
                    Logger::getInstance()->log('无效',0,'Jwt');
                    break;
                case  -2:
                    Logger::getInstance()->log('过期',0,'Jwt');
                break;
            }
        } catch (\EasySwoole\Jwt\Exception $e) {
            Logger::getInstance()->log($e->getMessage(),0,'Jwt'); 
        }
        
        return $data;
    }


}
