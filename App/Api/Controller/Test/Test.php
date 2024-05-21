<?php
namespace App\Api\Controller\Test;
use App\Api\Controller\BaseController;
use App\Api\Model\Share;
use App\Api\Utils\Keys;
use EasySwoole\ORM\DbManager;
use EasySwoole\RedisPool\RedisPool as Redis;
use App\Utility\RedisClient;

class Test extends BaseController
{

    public function index()
    {

        $objs = DbManager::getInstance()->invoke(function ($client) {
            $where = ['scene'=>'share','share_uid'=>'oualn5Fe8pbEm0zqDc0f8709rsmc'];
            return Share::invoke($client)->where($where)->all();
        });

        $uids = [];
        foreach ($objs as $obj) {
            $uids[] = $obj->toArray();
        }

        $easy = [];
        foreach ($uids as $key => $value) {
            # code...
            $easy[$value['scene']][$value['uid']] = ['create_time' => time()];
        }

        Redis::invoke(function (RedisClient $redis) use ($easy) {
            $redis->hSet('player:oualn5Fe8pbEm0zqDc0f8709rsmc','friend',json_encode($easy));
        },'redis');

        $this->rJson([]);
    }

}