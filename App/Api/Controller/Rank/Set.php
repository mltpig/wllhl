<?php
namespace App\Api\Controller\Rank;

use App\Api\Utils\Keys;
use App\Api\Service\RankService;
use App\Api\Controller\BaseController;

class Set extends BaseController
{
    public function index()
    {
        $uid      = $this->player->getData('uid');
        $province = $this->player->getData('province');
        if(empty($province)) $province = 35;

        switch ($this->param['rankenum'])
        {
            case 1:
                $key = Keys::getInstance()->getLeaderboardKey($province);
                RankService::getInstance()->updateLeaderboard($key,$this->param['score'],$uid);
                break;
            case 2:
                $key = Keys::getInstance()->getLeaderboardKey('endless');
                RankService::getInstance()->endlessLeaderboard($key,$this->param['score'],$uid);
            break;
        }

        $this->rJson([]);
    }
}