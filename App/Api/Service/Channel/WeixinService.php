<?php
namespace App\Api\Service\Channel;
use EasySwoole\HttpClient\HttpClient;
use EasySwoole\EasySwoole\Logger;
use EasySwoole\Component\CoroutineSingleTon;

class WeixinService
{
    use CoroutineSingleTon;

    private $appid  = 'wx68949348933f7b35';
    private $secret = 'c476bbe6198cd56f226b03703c2263d4';
    private $channelApi = 'https://api.weixin.qq.com/sns/jscode2session';

    public function getUserInfo(string $token)
    {
        $data = array( 
            'appid'      => $this->appid, 
            'secret'     => $this->secret,
            'js_code'    => $token,
            'grant_type' => 'authorization_code'
        );

        $httpClient = new HttpClient($this->channelApi);
        $httpClient->setQuery($data);
        $httpClient->setContentTypeJson();

        if($jsonStr = $httpClient->get()->getBody())
        {
            $chanData = json_decode($jsonStr,true);
            if(is_array($chanData) && array_key_exists('openid',$chanData) )
            {
                $result  = array(
                    'uid'          => $chanData['openid'],
                    'session_key'  => $chanData['session_key'],
                );
            }else{
                Logger::getInstance()->log('获取失败：'.$jsonStr,0,'Channel');
                $result = $chanData['errmsg'];
            }
        }else{
            Logger::getInstance()->log('渠道返回为空:'.$jsonStr,0,'Channel');
            $result = '渠道返回为空';
        }

        return $result;
    }

}