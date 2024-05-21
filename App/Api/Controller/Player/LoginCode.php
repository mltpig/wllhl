<?php
namespace App\Api\Controller\Player;
use App\Api\Service\Channel\WeixinService;
use EasySwoole\Http\AbstractInterface\Controller;

class LoginCode extends Controller
{
    public function index()
    {
        $param = $this->request()->getQueryParams();
        if(!$param){
            return $this->rJson(200,[]);
        }

        $data = WeixinService::getInstance()->getUserInfo($param['code']);
        
        if(!is_array($data)) return $this->rJson(200,[]);

        return $this->rJson(200,$data);
    }

    protected function rJson($statusCode = 200, $result = null):bool
    {
        if (!$this->response()->isEndResponse()) {
            $data = array(
                "code" => $statusCode,
                "result" => $result,
            );
            $this->response()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            $this->response()->withHeader('Content-type', 'application/json;charset=utf-8');
            $this->response()->withStatus(200);
            return true;
        } else {
            return false;
        }
    }
}