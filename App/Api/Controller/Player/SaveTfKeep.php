<?php
namespace App\Api\Controller\Player;
use App\Api\Controller\BaseController;

class SaveTfKeep extends BaseController
{

    public function index()
    {
        $this->player->setData('keep_tf',json_encode($this->param['data'],JSON_FORCE_OBJECT) );
        $this->rJson([]);
    }
}