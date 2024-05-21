<?php
namespace App\Api\Controller\Player;
use App\Api\Controller\BaseController;

class Save extends BaseController
{

    public function index()
    {
        $this->player->setData('extend',json_encode($this->param['data'],JSON_FORCE_OBJECT) );
        $this->rJson([]);
    }
}