<?php
namespace App\Api\Controller\Cdk;
use App\Api\Controller\BaseController;
use EasySwoole\ORM\DbManager;
use App\Api\Model\CdkLog;
use App\Api\Model\Cdk;

class RedeemCdk extends BaseController
{
    public function index()
    {
        $cdk = $this->param['cdk'];
        $uid = $this->player->getData('uid');

        $cdkModel = DbManager::getInstance()->invoke(function ($client) use ($cdk) {
            return Cdk::invoke($client)->where(['cdk' => $cdk])->get();
        });

        if(empty($cdkModel)) return $this->rJson('兑换码错误',true);

        $cdks = $cdkModel->toArray();

        $last = strtotime($cdks['start_time']);
        $end  = strtotime($cdks['end_time']);
        $time = time();

        if($time < $last || $time > $end) return $this->rJson('未生效或过期',true);

        // 1：一码一号; 2：一码通用
        if($cdks['type'] == 1){

            $cdklogModel = DbManager::getInstance()->invoke(function ($client) use ($cdk) {
                return CdkLog::invoke($client)->where(['cdk' => $cdk])->get();
            });

            // 没有cdk记录则领取
            if(empty($cdklogModel)){
                DbManager::getInstance()->invoke(function ($client) use ($cdk,$uid) {
                    CdkLog::invoke($client)->data(['cdk' => $cdk, 'player_id' => $uid, 'create_time' => date('Y-m-d H:i:s')])->save();
                });
            }else{
                return $this->rJson('兑换码以领取',true);
            }
        }else{

            $cdklogModel = DbManager::getInstance()->invoke(function ($client) use ($cdk,$uid) {
                return CdkLog::invoke($client)->where(['cdk' => $cdk,'player_id' => $uid])->get();
            });

            // 没有该用户领取的cdk记录则领取
            if(empty($cdklogModel)){
                DbManager::getInstance()->invoke(function ($client) use ($cdk,$uid) {
                    CdkLog::invoke($client)->data(['cdk' => $cdk, 'player_id' => $uid, 'create_time' => date('Y-m-d H:i:s')])->save();
                });
            }else{
                return $this->rJson('兑换码以领取',true);
            }
        }
        
        $result = json_decode($cdks['prop'],true);
        $this->rJson($result);
    }
}