<?php
namespace App\Api\Controller\Rank;

use App\Api\Utils\Keys;
use App\Api\Service\RankService;
use App\Api\Controller\BaseController;

class SetCustom extends BaseController
{
    public function index()
    {

        $uid      = $this->player->getData('uid');
        $key = Keys::getInstance()->getLeaderboardKey($this->param['rankname']);
        
        //兼容客户端BUG盗刷江湖地位
        // if($this->param['rankname'] == "pos"){
        //     if($this->param['score'] > 35) $this->param['score'] = 1;
        // }
        
        RankService::getInstance()->customLeaderboard($key,$this->param['score'],$uid);

        $this->rJson([]);
    }
}