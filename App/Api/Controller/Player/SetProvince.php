<?php
namespace App\Api\Controller\Player;
use App\Api\Utils\Keys;
use EasySwoole\RedisPool\RedisPool as Redis;
use App\Utility\RedisClient;
use App\Api\Service\RankService;
use App\Api\Controller\BaseController;

class SetProvince extends BaseController
{

    public function index()
    {
        $newProvince = $this->param['province'];
        $oldProvince = $this->player->getData('province');

        $uid   = $this->player->getData('uid');

        if($newProvince != $oldProvince)
        {
            $new = Keys::getInstance()->getLeaderboardKey($newProvince);
            $old = Keys::getInstance()->getLeaderboardKey($oldProvince);

            $score = Redis::invoke(function (RedisClient $redis) use ($uid) {
                return $redis->zScore(Keys::getInstance()->getLeaderboardKey('all'),$uid);
            },'redis');

            if(!$score) $score = 0;
    
            RankService::getInstance()->updateProvinceRanking($new,$old,$uid,$score);
            
            $this->player->setData('province',$newProvince);
        }
 
        $this->rJson(['uid' => $this->player->getData('uid'),'province'=> KV_PROVINCE[$newProvince] ]);

    }
}