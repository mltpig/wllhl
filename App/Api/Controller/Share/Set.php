<?php
namespace App\Api\Controller\Share;
use App\Api\Service\PlayerService;
use App\Api\Controller\BaseController;

class Set extends BaseController
{

    public function index()
    {

        $result = '无效的场景值';
        if(in_array($this->param['scene'],['sx','share']))
        {
            if($this->param['playerid'] !== $this->param['shareuid'])
            {
                $sharePlayer = new PlayerService($this->param['shareuid']);
                $result = '无效的shareuid';
                if(!is_null($sharePlayer->getData('login_time')) )
                {
                    $time = time();
                    $firendData = ['create_time' => $time];

                    $sharePlayer->setFriendData($this->param['scene'],$this->param['playerid'],$firendData,'set');
                    $sharePlayer->saveData();

                    if($this->param['scene'] === 'sx')
                    {    
                        $firendData = ['create_time' => $time];
                        $this->player->setFriendData($this->param['scene'],$this->param['shareuid'],$firendData,'set');
                    }
                    $result = [];
                }
            }
        } 

        $this->rJson($result);
    }

}