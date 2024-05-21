<?php

namespace App\Api\Service;

use App\Api\Model\Player;
use App\Api\Utils\Keys;
use EasySwoole\ORM\DbManager;
use EasySwoole\RedisPool\RedisPool as Redis;
use App\Utility\RedisClient;

class BaseService
{
    protected $uid             = null; //String   ID
    protected $extend          = null; //String   玩家档案
    protected $keep_wl         = null; //String   档案
    protected $keep_tf         = null; //String   档案
    protected $knapsack        = null; //String   背包
    protected $avatar          = null; //String   头像
    protected $name            = null; //String   昵称
    protected $province        = null; //String   省份
    protected $login_time      = null; //String   最后一次存档时间
    public    $friend          = null; //array    好友
    public    $playerKey       = null; //String   玩家KEY
    protected $outField = ['outField','playerKey'];

    public function __construct(string $playerid)
    {
        $this->uid = $playerid;
        $this->playerKey = Keys::getInstance()->getPlayerKey($playerid);

        $this->getPlayerInfo();
    }

    //获取用户数据
    public function getPlayerInfo(): void
    {
        if ($userData = $this->findUserData()) $this->init($userData);
    }

    //查找用户
    public function findUserData(): array
    {
        $userCache = Redis::invoke(function (RedisClient $redis) {
            return $redis->hGetAll($this->playerKey);
        },'redis');

        // TODD:缓存双写
        if (!empty($userCache)){
            Redis::invoke(function (RedisClient $redis){
                $redis->sAdd(USER_SET,$this->playerKey);
            },'redis');
            return $userCache;
        }

        $userObj = DbManager::getInstance()->invoke(function ($client) {
            return Player::invoke($client)->get(['uid' => $this->uid]);
        });

        if (is_null($userObj)) return array();

        return $this->mysql2Cache($userObj->toArray());
    }

    //用户数据初始化
    private function init(array $userData): void
    {
        foreach ($userData as $name => $value) 
        {
            if (!property_exists($this, $name) || in_array($name,$this->outField)) continue;
            $array = $value;
            $this->{$name} = is_array($array) ? $array : $value;
        }
    }

    //注册
    public function signup($provinceid): bool
    {

        $userData = array(
            'uid'             => $this->uid,
            'avatar'          => '',
            'name'            => '',
            'province'        => $provinceid,
            'admission_fee'   => 0,
            'extend'          => json_encode([],JSON_FORCE_OBJECT),
            'keep_wl'         => json_encode([],JSON_FORCE_OBJECT),
            'keep_tf'         => json_encode([],JSON_FORCE_OBJECT),
            'knapsack'        => json_encode([],JSON_FORCE_OBJECT),
            'friend'          => json_encode([],JSON_FORCE_OBJECT),
            'login_time'      => date('Y-m-d H:i:s'),
            'create_time'     => date('Y-m-d H:i:s'),
        );

        try {

            $incrId = DbManager::getInstance()->invoke(function ($client) use ($userData) {
                return  Player::invoke($client)->data($userData)->save();
            });

            if (is_null($incrId)) return false;
            
            $this->init($this->mysql2Cache($userData));
            return true;
        } catch (\Throwable $th) {
            \EasySwoole\EasySwoole\Logger::getInstance()->info("inster Error " . $th->getMessage());
            return false;
        }
    }

    //mysql数据格式转化为缓存数据格式
    private function mysql2Cache(array $userInfo): array
    {
        $playerData = array();
        foreach ($userInfo as $name => $val) 
        {
            if (!property_exists($this, $name)) continue;
            $playerData[$name] = $val ;
        }

        Redis::invoke(function (RedisClient $redis) use ($playerData) {
            $redis->hMSet($this->playerKey, $playerData);
            $redis->sAdd(USER_SET,$this->playerKey);
        },'redis');

        return $playerData;
    }

    //保存用户数据至Redis
    public function saveData()
    {
        $newData = array();
        foreach ($this as $name => $value) 
        {
            if (!property_exists($this, $name) || is_null($value) || in_array($name,$this->outField)) continue;
            $newData[$name] = is_array($value) ? json_encode($value) : $value;
        }
        Redis::invoke(function (RedisClient $redis) use ($newData) {
            $redis->hMSet($this->playerKey, $newData);
        },'redis');
    }

    //获取用户字段数据入口
    public function getData(string $field)
    {
        if (!property_exists($this, $field)) throw new \Exception($field . " 属性不存在");
        return $this->{$field};
    }

    //设置用户数据，专属string类型
    public function setData(string $field, string $data): void
    {
        if (!property_exists($this, $field)) throw new \Exception($field . " 属性不存在");
        $this->{$field} = $data;
    }

}
