<?php
namespace App\Api\Controller\Rank;

use App\Api\Utils\Keys;
use App\Api\Service\RankService;
use App\Api\Controller\BaseController;

class Get extends BaseController
{
    public function index()
    {
        $uid = $this->player->getData('uid');
        $name = $this->player->getData('name');
        $avatar = $this->player->getData('avatar');

        switch ($this->param['rankenum']){
            case 1:
                //  全国玩家排行榜
                $key = Keys::getInstance()->getLeaderboardKey('all');
                list($myData,$worldData) = RankService::getInstance()->getWorldPlayersLeaderboard($key,$uid);
                $myData['name']     = $name;
                $myData['avatar']   = $avatar;
                break;
            case 2:
                //  全省积分排行榜
                list($myData,$worldData) = RankService::getInstance()->getAllProvincesLeaderboard($uid);
                break;
            case 3:
                //  各省玩家排行榜
                list($myData,$worldData) = RankService::getInstance()->getAllPlayersLeaderboardInProvince($uid);
                break;
            case 4:
                //  玩家无尽排行榜
                $key = Keys::getInstance()->getLeaderboardKey('endless');
                list($myData,$worldData) = RankService::getInstance()->getWorldPlayersLeaderboard($key,$uid);
                $myData['name']     = $name;
                $myData['avatar']   = $avatar;
                break;
            default:
                $myData = $worldData = [];
        }

        $this->rJson([
            'myInfo' => $myData , 
            'worldInfo' => $worldData
        ]);
    }
}