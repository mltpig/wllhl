<?php
namespace App\Api\Controller\Player;
use App\Api\Controller\BaseController;

class SaveWlKeep extends BaseController
{

    public function index()
    {
        $this->player->setData('keep_wl',json_encode($this->param['data'],JSON_FORCE_OBJECT) );
        $this->rJson([]);
    }
}