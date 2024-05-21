<?php
namespace App\Api\Controller\Player;
use App\Api\Utils\Keys;
use EasySwoole\RedisPool\RedisPool as Redis;
use App\Utility\RedisClient;
use App\Api\Service\playerService;
use App\Api\Controller\BaseController;

class GetUidExtend extends BaseController
{
    public function index()
    {
        $this->rJson([
            'uid'       => $this->player->getData('uid'),
            'extend'    => json_decode($this->player->getData('extend'),true),
            'keep_wl'   => json_decode($this->player->getData('keep_wl'),true),
            'keep_tf'   => json_decode($this->player->getData('keep_tf'),true),
            'knapsack'  => json_decode($this->player->getData('knapsack'),true),
        ]);
    }
}