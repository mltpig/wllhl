<?php
namespace App\Api\Controller\Share;
use App\Api\Controller\BaseController;

class Get extends BaseController
{

    public function index()
    {
        $result = '无效的场景值';

        if(in_array($this->param['scene'],['sx','share']))
        {
            $result = $this->player->getFriendData($this->param['scene']);
        }

        $this->rJson($result);
    }

}