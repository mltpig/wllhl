<?php


namespace EasySwoole\EasySwoole;


use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\EasySwoole\Swoole\EventRegister;


use EasySwoole\EasySwoole\Config as GlobalConfig;

use EasySwoole\ORM\DbManager;
use EasySwoole\ORM\Db\Config as DbConfig;
use EasySwoole\ORM\Db\Connection;

use EasySwoole\Redis\Config\RedisConfig;
use EasySwoole\RedisPool\RedisPool;
use App\Utility\RedisClient;

use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use EasySwoole\Component\Di;
use EasySwoole\EasySwoole\SysConst;

use App\Crontab\PlayerCacheToMysql;

class EasySwooleEvent implements Event
{
    public static function initialize()
    {
        //redis
        $redisConfig = GlobalConfig::getInstance()->getConf('REDIS');
        RedisPool::getInstance()->register(new RedisConfig($redisConfig),'redis',RedisClient::class);

        //mysql
        $config = new DbConfig(GlobalConfig::getInstance()->getConf("MYSQL"));
        DbManager::getInstance()->addConnection(new Connection($config));
        
        //cosr
        Di::getInstance()->set(SysConst::HTTP_GLOBAL_ON_REQUEST,function (Request $request, Response $response){
            $response->withHeader('Access-Control-Allow-Origin', '*');
            $response->withHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
            $response->withHeader('Access-Control-Allow-Credentials', 'true');
            $response->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
            if ($request->getMethod() === 'OPTIONS') {
                $response->withStatus(\EasySwoole\Http\Message\Status::CODE_OK);
                return false;
            }
            return true;
        });

    }

    public static function mainServerCreate(EventRegister $register)
    {
        // 配置定时任务
        $crontabConfig = new \EasySwoole\Crontab\Config();

        // 1.设置执行定时任务的 socket 服务的 socket 文件存放的位置，默认值为 当前文件所在目录
        // 这里设置为框架的 Temp 目录
        $crontabConfig->setTempDir(EASYSWOOLE_TEMP_DIR);

        // 2.设置执行定时任务的 socket 服务的名称，默认值为 'EasySwoole'
        $crontabConfig->setServerName('EasySwoole');

        // 3.设置用来执行定时任务的 worker 进程数，默认值为 3
        $crontabConfig->setWorkerNum(3);

        // 4.设置定时任务执行出现异常的异常捕获回调
        $crontabConfig->setOnException(function (\Throwable $throwable) {
            // 定时任务执行发生异常时触发（如果未在定时任务类的 onException 中进行捕获异常则会触发此异常回调）
        });

        // 创建定时任务实例
        $crontab = \EasySwoole\EasySwoole\Crontab\Crontab::getInstance($crontabConfig);

        // 注册定时任务
        $crontab->register(new PlayerCacheToMysql());
    }
}