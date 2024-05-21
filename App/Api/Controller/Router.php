<?php
namespace App\Api\Controller;
use FastRoute\RouteCollector;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use EasySwoole\Http\AbstractInterface\AbstractRouter;

class Router extends AbstractRouter
{
    function initialize(RouteCollector $routeCollector)
    {
        $this->setMethodNotAllowCallBack(function (Request $request,Response $response){
            $response->write('403');
            return false;
        });
        $this->setRouterNotFoundCallBack(function (Request $request,Response $response){
            $response->write('404');
            return false;
        });

        $routeCollector->post('/'.CHANNEL_TAGE.'/test','/Test/Test/index');

        $routeCollector->get('/'.CHANNEL_TAGE.'/byteCode','/Player/LoginCode/index');
        $routeCollector->post('/'.CHANNEL_TAGE.'/byteUid','/Player/Login/index');
        $routeCollector->post('/'.CHANNEL_TAGE.'/getProfile','/Player/Get/index');

        $routeCollector->post('/'.CHANNEL_TAGE.'/saveProfile','/Player/Save/index');
        $routeCollector->post('/'.CHANNEL_TAGE.'/saveTfKeep','/Player/SaveTfKeep/index');
        $routeCollector->post('/'.CHANNEL_TAGE.'/saveWlKeep','/Player/SaveWlKeep/index');

        $routeCollector->post('/'.CHANNEL_TAGE.'/saveKnapsack','/Player/SaveKnapsack/index');

        $routeCollector->post('/'.CHANNEL_TAGE.'/setAvatarAndNickname','/Player/Set/index');
        $routeCollector->post('/'.CHANNEL_TAGE.'/getUidExtend','/Player/GetUidExtend/index');
        // $routeCollector->post('/'.CHANNEL_TAGE.'/getProvince','/Player/GetProvince/index');
        $routeCollector->post('/'.CHANNEL_TAGE.'/setProvince','/Player/SetProvince/index');

        $routeCollector->post('/'.CHANNEL_TAGE.'/getLeaderboard','/Rank/Get/index');
        $routeCollector->post('/'.CHANNEL_TAGE.'/setLeaderboard','/Rank/Set/index');
        $routeCollector->post('/'.CHANNEL_TAGE.'/getCustomLeaderboard','/Rank/GetCustom/index');
        $routeCollector->post('/'.CHANNEL_TAGE.'/setCustomLeaderboard','/Rank/SetCustom/index');
        
        $routeCollector->post('/'.CHANNEL_TAGE.'/invitation','/Share/Set/index');
        $routeCollector->post('/'.CHANNEL_TAGE.'/invitationOnFriend','/Share/Get/index');
        $routeCollector->post('/'.CHANNEL_TAGE.'/invitationDel','/Share/Del/index');

        $routeCollector->post('/'.CHANNEL_TAGE.'/redeemCdk','/Cdk/RedeemCdk/index');

        $routeCollector->get('/'.CHANNEL_TAGE.'/notice','/Conf/Notice/index');
    }


}