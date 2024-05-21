<?php
namespace App\Api\Controller\Player;
use App\Api\Utils\Jwt;
use App\Api\Controller\BaseController;

class Login extends BaseController
{

    public function index()
    {
        $this->rJson([
            'uid'       => $this->player->getData('uid'),
            'avatar'    => $this->player->getData('avatar'),
            'name'      => $this->player->getData('name'),
            'playerid'  => $this->player->getData('uid'),
            'province'  => KV_PROVINCE[$this->player->getData('province')],
            'state'     => 1,
            'extend'    => json_decode($this->player->getData('extend'),true),
            'keep_wl'   => json_decode($this->player->getData('keep_wl'),true),
            'keep_tf'   => json_decode($this->player->getData('keep_tf'),true),
            'knapsack'  => json_decode($this->player->getData('knapsack'),true),
            //  'playerid'  => Jwt::getInstance()->encode($this->param['uid']),
        ]);
    }
}