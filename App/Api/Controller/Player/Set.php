<?php
namespace App\Api\Controller\Player;
use App\Api\Utils\Keys;
use App\Api\Service\RankService;
use App\Api\Controller\BaseController;

class Set extends BaseController
{

    public function index()
    {
        $this->player->setData('name',$this->param['name']);
        $this->player->setData('avatar',$this->param['avatar']);
        $this->rJson([]);
    }
}