<?php

namespace App\Api\Utils;
use App\Api\Utils\Keys;
use EasySwoole\RedisPool\RedisPool as Redis;
use App\Utility\RedisClient;
use EasySwoole\Component\CoroutineSingleTon;

class Lock
{
    use CoroutineSingleTon;

    public function exists(string $uid):bool
    {
        $lockKey  = Keys::getInstance()->getPlayerLockKey($uid);
        return Redis::invoke(function (RedisClient $redis) use ($lockKey) {
            if($redis->exists($lockKey)) return true;
            $redis->set($lockKey,date('Y-m-d H:i:s'),1);
            return false;
        },'redis');
    }
    
    public function rem(string $uid):void
    {
        $lockKey  = Keys::getInstance()->getPlayerLockKey($uid);
        Redis::invoke(function (RedisClient $redis) use ($lockKey) {
            $redis->unlink($lockKey);
        },'redis');
    }
}