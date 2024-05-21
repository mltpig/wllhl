<?php
namespace App\Api\Controller\Share;
use App\Api\Controller\BaseController;

class Del extends BaseController
{

    public function index()
    {
        $result = '无效的场景值';
        if(in_array($this->param['scene'],['sx','share'])){
            $this->player->setFriendData($this->param['scene'],$this->param['uid'],[],'delete');
            $result = [];
        }
        $this->rJson($result);
    }

}