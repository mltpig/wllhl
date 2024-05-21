<?php
namespace App\Api\Controller\Rank;
use App\Api\Utils\Keys;
use App\Api\Service\RankService;
use App\Api\Controller\BaseController;

class GetCustom extends BaseController
{
    public function index()
    {

        $uid    = $this->player->getData('uid');
        $name   = $this->player->getData('name');
        $avatar = $this->player->getData('avatar');

        $key = Keys::getInstance()->getLeaderboardKey($this->param['rankname']);
        list($myData,$worldData) = RankService::getInstance()->getWorldPlayersLeaderboard($key,$uid);

        $myData['name']     = $name;
        $myData['avatar']   = $avatar;
        $result = ['myInfo' => $myData , 'worldInfo' => $worldData];

        $this->rJson($result);
    }
}