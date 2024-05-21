<?php

namespace App\Api\Service;

use EasySwoole\RedisPool\RedisPool as Redis;
use App\Utility\RedisClient;
use App\Api\Utils\Keys;

class PlayerService extends BaseService
{
    
    public function check():void
    {
        $now = time();
        //新增字段默认值设置
        $this->newAddFieldDefaultSetValue();
        $this->setData('login_time',date('Y-m-d H:i:s',$now));
    }


    public function newAddFieldDefaultSetValue():void
    {

    }

    public function setFriendData(string $scene, string $uid,array $data,string $action): void
    {
        //_time_ 作为上次刷新记录时间戳
        $friend = $this->friend;
        if(empty($friend)) $friend = json_encode([],JSON_FORCE_OBJECT);

        $friend = json_decode($friend,true);
        switch ($action) 
        {
            case 'set':
                if($scene === 'share' && isset($friend[$scene][$uid])) return;
                $friend[$scene][$uid] = $data;
                $this->friend = json_encode($friend);

                if($scene === 'sx') $this->checkSx();
            break;
            case 'delete':
                unset($friend[$scene][$uid]);
                $this->friend = json_encode($friend);
            break;
        }
    }

    public function getFriendData(string $scene):array
    {
        $list    = [];
        $friend  = $this->getData('friend');
        if(empty($friend)) $friend = json_encode([],JSON_FORCE_OBJECT);

        $friend  = json_decode($friend,true);
        if(!array_key_exists($scene,$friend)) return $list;

        foreach ($friend[$scene] as $uid => $info)
        {
            $player = new PlayerService($uid);
            $list[] = [
                'uid'           => $uid,
                'name'          => $player->getData('name'),
                'avatar'        => $player->getData('avatar'),
                'create_time'   => $info['create_time'],
            ];
        }
        return array_values($list);
    }

    public function checkSx():void
    {
        $friend  = $this->getData('friend');
        if(empty($friend)) $friend = json_encode([],JSON_FORCE_OBJECT);

        $friend  = json_decode($friend,true);
        if(!array_key_exists('sx',$friend) || count($friend['sx']) <= 3) return ;

        $list    = [];
        foreach ($friend['sx'] as $uid => $info)
        {
            $list[intval($info['create_time'])] = [
                'uid'           => $uid,
                'create_time'   => $info['create_time'],
            ];
        }
        ksort($list);

        $list = array_slice($list,-3,3);

        $newList = [];
        foreach ($list as $key => $data) 
        {
            $newList[$data['uid']] = ['create_time' => $data['create_time']];
        }
        $friend['sx'] = $newList;

        $this->friend = json_encode($friend);
    }

}
