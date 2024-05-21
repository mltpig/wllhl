<?php
namespace App\Api\Service\Channel;
use EasySwoole\HttpClient\HttpClient;
use EasySwoole\EasySwoole\Logger;
use EasySwoole\Component\CoroutineSingleTon;

class TencentIpService
{
    use CoroutineSingleTon;
    
    private $url = 'https://apis.map.qq.com/ws/location/v1/ip';
    private $key = 'TYXBZ-LW5LJ-MJLFE-FQ6D5-DZIDH-ZQBTJ';

	public function getProvinceId(string $clientIp):int
	{

        $param = [ 'key' => $this->key,'ip'  => $clientIp ];
        $province = '未知';

        try {
            
            $client = new HttpClient($this->url);
            $client->setQuery( $param );
            $client->setTimeout(5);
            $result = $client->get()->json(true);

            if($result['status'] === 0)
            {
                if(isset($result['result']['ad_info']['province']))
                {
                    $province = str_replace('省','',$result['result']['ad_info']['province']);
                }else{
                    Logger::getInstance()->waring('TencentIpService：'.json_encode($param).'  resule : '.json_encode($result));
                }
            }else{
                Logger::getInstance()->waring('TencentIpService：'.json_encode($param).' resule : '.json_encode($result));
            }
        } catch (\Throwable $th) {
            Logger::getInstance()->waring('TencentIpService：'.$th->getMessage().'  param: '.json_encode($param) );
        }

        return array_key_exists($province,VK_PROVINCE) ? VK_PROVINCE[$province] : 35;
	}
}
