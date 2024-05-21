<?php
namespace App\Api\Controller;
use App\Api\Validate\Check;
use EasySwoole\EasySwoole\Logger;
use App\Api\Utils\Lock;
use App\Api\Service\PlayerService;
use App\Api\Service\BlackService;
use EasySwoole\EasySwoole\ServerManager;
use App\Api\Service\Channel\TencentIpService;
use EasySwoole\Http\AbstractInterface\Controller;

class BaseController extends Controller
{
    public $player;
    public $param;
    public $uri;

    protected function onRequest(?string $action): ?bool
    {

        $tag   = CHANNEL_TAGE.'/';
        $this->uri = ltrim($this->request()->getServerParams()['request_uri'],'/');
        strpos($this->uri , $tag) !== 0 ? : $this->uri = substr($this->uri,strlen($tag));

        if(!$body = $this->request()->getBody()->__toString()) return $this->rJson(40020,true);

        $request = json_decode($body,true);
        if(!is_array($request)) return $this->rJson(40020,true);

        $result  = Check::getInstance()->getValidateData($this->uri,$request);
        if(array_key_exists('code',$result)) return $this->rJson($result['msg'],true);

        // if(Lock::getInstance()->exists($result['playerid'])) return $this->rJson(40010,true);
        $police = BlackService::getInstance()->blackList($result['playerid']);
        if($police) return $this->rJson('账号封禁,请勿修改 客服群：299414346',true);
        
        if($this->uri === 'test') return true;

        $this->param = $result;
        $this->player = new PlayerService($result['playerid']);
        //注册用户单独至login接口
        if($this->uri === 'byteUid' && is_null($this->player->getData('login_time')))
        {
            $provinceid = TencentIpService::getInstance()->getProvinceId($this->clientRealIP());
            if(!$this->player->signup($provinceid)) return $this->rJson(40010,true);
        }

        $this->player->check();

        return true;
    }

    protected function rJson($result,bool $force = false):bool
    {
        if (!$this->response()->isEndResponse()) 
        {
            switch ($result) {
                case 40010:
                    $data = [ "code"=> 40010 , "msg" => '请勿频繁请求'];
                    break;
                case 40020:
                    $data = [ "code"=> 40020 , "msg" => '请求为空异常的数据'];
                    break;
                default:
                    $data = !is_array($result) ? [ "code"=> ERROR , "msg" => $result ]: [ "code"=> SUCCESS , "data" => $result ];
                    break;
            }
            
            $this->response()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_FORCE_OBJECT));
            $this->response()->withHeader('Content-type', 'application/json;charset=utf-8');
            $this->response()->withStatus(200);

            return $force === false ? true : false;
        } else {
            return false;
        }
    }

    protected function gc()
    {

        if( $this->player instanceof PlayerService )
        {
            if(!is_null($this->player->getData('login_time'))) $this->player->saveData();
            Lock::getInstance()->rem($this->param['playerid']);
        }

        parent::gc();
    }

    protected function clientRealIP($headerName = 'x-real-ip')
    {
        $server = ServerManager::getInstance()->getSwooleServer();
        $client = $server->getClientInfo($this->request()->getSwooleRequest()->fd);

        $clientAddress = $client['remote_ip'];
        $xri = $this->request()->getHeader($headerName);
        $xff = $this->request()->getHeader('x-forwarded-for');

        if ($clientAddress === '127.0.0.1'){
            if (!empty($xri)) {  // 如果有 xri 则判定为前端有 NGINX 等代理
                $clientAddress = $xri[0];
            } elseif (!empty($xff)) {  // 如果不存在 xri 则继续判断 xff
                $list = explode(',', $xff[0]);
                if (isset($list[0])) $clientAddress = $list[0];
            }
        }
        return $clientAddress;
    }

}