<?php
namespace App\Api\Controller\Player;
use App\Api\Controller\BaseController;

class SaveKnapsack extends BaseController
{

    public function index()
    {
        $this->player->setData('knapsack',json_encode($this->param['data'],JSON_FORCE_OBJECT) );
        $this->rJson([]);
    }
}