<?php
namespace App\Crontab;
use EasySwoole\EasySwoole\Task\TaskManager;
use \EasySwoole\Crontab\JobInterface;
use App\Api\Model\Player;
use App\Api\Utils\Keys;
use EasySwoole\EasySwoole\Logger;
use EasySwoole\RedisPool\RedisPool as Redis;
use App\Utility\RedisClient;

class PlayerCacheToMysql implements JobInterface
{
    public function crontabRule(): string
    {
        // 定义执行规则 根据Crontab来定义
        return '0 5 * * *';
    }

    public function jobName(): string
    {
        // 定时任务的名称
        return '玩家数据入库';
    }

    public function run()
    {
        // 开发者可投递给task异步处理
        TaskManager::getInstance()->async(function (){

            Redis::invoke(function (RedisClient $redis) {
                $num   = 0;
                $fail  = array('find' => array(),'update'=>array());
                while ($playerKey = $redis->spop(USER_SET)) 
                {
                    
                    if($userInfo = $redis->hgetall($playerKey))
                    {
                        if(!isset($userInfo['name'])) $userInfo['name'] = '';
                        if(!isset($userInfo['avatar'])) $userInfo['avatar'] = '';
                        if(!isset($userInfo['province'])) $userInfo['province'] = 35;
                        if(!isset($userInfo['keep_wl'])) $userInfo['keep_wl'] = json_encode([],JSON_FORCE_OBJECT);
                        if(!isset($userInfo['keep_tf'])) $userInfo['keep_tf'] = json_encode([],JSON_FORCE_OBJECT);
                        if(!isset($userInfo['knapsack'])) $userInfo['knapsack'] = json_encode([],JSON_FORCE_OBJECT);
                        if(!isset($userInfo['friend'])) $userInfo['friend'] = json_encode([],JSON_FORCE_OBJECT);

                        $userField = [
                            'name'          => $userInfo['name'],
                            'avatar'        => $userInfo['avatar'],
                            'province'      => $userInfo['province'],
                            'keep_wl'       => $userInfo['keep_wl'],
                            'keep_tf'       => $userInfo['keep_tf'],
                            'knapsack'      => $userInfo['knapsack'],
                            'friend'        => $userInfo['friend'],
                            'login_time'    => $userInfo['login_time'],
                        ];

                        if(Player::create()->update($userInfo,['uid' => $userInfo['uid']]))
                        {
                            $num++;
                            $redis->unlink($playerKey);
                        }else{
                            $fail['update'][] = $playerKey;
                        }
                    }else{
                        $fail['find'][] = $playerKey;
                    }
    
                }
                Logger::getInstance()->waring(' 成功更新日活 ： '.$num.' === 失败 ： '.json_encode($fail));
            },'redis');
        });

    }

    public function onException(\Throwable $throwable)
    {
        // 捕获run方法内所抛出的异常
    }
}