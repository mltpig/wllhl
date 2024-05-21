<?php
namespace App\Api\Utils;
use EasySwoole\Component\CoroutineSingleTon;

class Keys 
{
    use CoroutineSingleTon;

    public function getPlayerKey(string $uid):string
    {   
        return 'player:'.$uid;
    }

    public function getPlayerLockKey(string $uid):string
    {   
        return 'lock:'.$uid;
    }

    public function getLeaderboardKey(string $key):string
    {   
        return 'rank:'.$key;
    }
}
