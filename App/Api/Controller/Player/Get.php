<?php
namespace App\Api\Controller\Player;
use App\Api\Controller\BaseController;

class Get extends BaseController
{

    public function index()
    {
        $result = [
            'time'      => time(),
            'uid'       => $this->player->getData('uid'),
            'name'      => $this->player->getData('name'),
            'avatar'    => $this->player->getData('avatar'),
            'state'     => 1,
            'province'  => KV_PROVINCE[$this->player->getData('province')],
            'extend'    => json_decode($this->player->getData('extend'),true),
            'keep_wl'   => json_decode($this->player->getData('keep_wl'),true),
            'keep_tf'   => json_decode($this->player->getData('keep_tf'),true),
            'knapsack'  => json_decode($this->player->getData('knapsack'),true),
        ];
        $this->rJson($result);
    }
}