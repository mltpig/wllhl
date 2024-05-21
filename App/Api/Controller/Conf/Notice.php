<?php
namespace App\Api\Controller\Conf;

use EasySwoole\Http\AbstractInterface\Controller;

class Notice extends Controller
{
    public function index()
    {
        $param = $this->request()->getQueryParams();

        $data = [
            'time' => time(),
            'title' => '公告',
            'content' => "尊敬的各位大侠：\n感谢您对《武林轮回录》的支持与喜爱\n我们为大家精心准备了假期福利~\n各位大侠点击游戏中设置选择兑换码输入\n以下五个兑换码：\nwykl、wy666、wy888、wy999、wy000\n即可领取福利~\n\n游戏目前还在优化阶段，如遇BUG或者如\n有建议，可通过以下方式联系我们会在\n第一时间处理\n感谢各位大侠的支持~\n武林轮回录官方客服：\n3003942926\n武林轮回录官方玩家交流群：\n299414346\n发件人：《武林轮回录》运营团队",
        ];

        $this->rJson(200,$data,'');
    }


    protected function rJson($statusCode = 200, $result = null, $msg = null):bool
    {
        if (!$this->response()->isEndResponse()) {
            $data = array(
                "code" => $statusCode,
                "result" => $result,
                "msg" => $msg
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