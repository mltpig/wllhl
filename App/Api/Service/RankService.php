<?php
namespace App\Api\Service;

use EasySwoole\RedisPool\RedisPool as Redis;
use App\Utility\RedisClient;
use App\Api\Utils\Keys;
use App\Api\Service\PlayerService;
use EasySwoole\Component\CoroutineSingleTon;

class RankService
{
    use CoroutineSingleTon;

    // 更新世界和省排行榜
    public function updateLeaderboard(string $key, int $score, string $uid):void
    {
        Redis::invoke(function (RedisClient $redis) use ($key,$score,$uid) {
            // 世界排行积分
            $all = Keys::getInstance()->getLeaderboardKey('all');
            $redis->zAdd($all,$score,$uid);
            // 省排行积分
            $redis->zAdd($key,$score,$uid);
        },'redis');
    }

    // 更新无尽排行榜
    public function endlessLeaderboard(string $key, int $score, string $uid):void
    {
        Redis::invoke(function (RedisClient $redis) use ($key,$score,$uid) {
            $redis->zAdd($key,$score,$uid);
        },'redis');
    }

    // 更新自定义排行榜
    public function customLeaderboard(string $key, int $score, string $uid):void
    {
        Redis::invoke(function (RedisClient $redis) use ($key,$score,$uid) {
            $redis->zAdd($key,$score,$uid);
        },'redis');
    }

    // 更新修改省份排行榜
    public function updateProvinceRanking(string $new, string $old, string $uid, int $score):void
    {
        $this->delectLeaderboard($old,$uid);
        Redis::invoke(function (RedisClient $redis) use ($new,$score,$uid) {
            // 变更省份积分为0
            if($score > 0) $redis->zAdd($new,$score,$uid); 
        },'redis');
    }

    // 删除排行榜
    public function delectLeaderboard(string $key, string $uid):void
    {
        Redis::invoke(function (RedisClient $redis) use ($key,$uid) {
            $redis->zRem($key,$uid);
        },'redis');
    }

    // 查询标准化排行榜
    public function getWorldPlayersLeaderboard(string $key, string $uid, int $len = 49):array
    {
        $rankInfo = Redis::invoke(function (RedisClient $redis) use ($key,$uid,$len) {
            return [
                "myIndex"   => $redis->zRevRank($key,$uid),
                "myScore"   => $redis->zScore($key,$uid),
                "worldData" => $redis->zRevRange($key,0,$len,true)
            ];
        },'redis');

        return [
            [
                'index' => is_null($rankInfo['myIndex'])  ? 0 : ++$rankInfo['myIndex'],
                'score' => is_null($rankInfo['myScore']) ? 0 : numToStr($rankInfo['myScore']),
            ],
            $this->formatWorldLeaderboard($rankInfo['worldData']),
        ];
    }

    // 查询全省排行榜
    public function getAllProvincesLeaderboard(string $uid, int $len = 100):array
    {
        $rankInfo = Redis::invoke(function (RedisClient $redis) use ($uid,$len) {
            $aggregation = [];
            // 循环各省份排行内玩家
            foreach (VK_PROVINCE as $k => $v) {
                $key     = Keys::getInstance()->getLeaderboardKey($v);
                $players = $redis->zRevRange($key,0,$len,true);

                // 各省份玩家分数总和
                $score = 0;
                foreach ($players as $player => $number) {
                    $score += $number;
                }
                $aggregation[$k] = $score;
            }

            // 省份排行排序
            array_multisort($aggregation,SORT_DESC,SORT_NUMERIC);

            // 玩家所在省份排名以及积分
            $playerkey  = Keys::getInstance()->getPlayerKey($uid);
            $province   = $redis->hGet($playerkey,'province');
            
             if(empty($province) || $province > 35) $province = 35;
             
            $keys   = array_keys($aggregation);
            $index  = array_search(KV_PROVINCE[$province],$keys);

            return [
                "myName"    => KV_PROVINCE[$province],
                "myIndex"   => $index + 1,
                "myScore"   => $aggregation[KV_PROVINCE[$province]],
                "worldData" => $aggregation,
            ];
        },'redis');

        return [
            [
                'name'  => $rankInfo['myName'],
                'index' => $rankInfo['myIndex'],
                'score' => $rankInfo['myScore'],
            ],
            $this->formatAllProvincesLeaderboard($rankInfo['worldData']),
        ];
    }

    // 查询各省内玩家排行榜
    public function getAllPlayersLeaderboardInProvince(string $uid, int $len = 10):array
    {
        $rankInfo = Redis::invoke(function (RedisClient $redis) use ($uid,$len) {
            $aggregation = [];
            // 循环各省份排行内玩家
            foreach (VK_PROVINCE as $k => $v) {
                $key     = Keys::getInstance()->getLeaderboardKey($v);
                $players = $redis->zRevRange($key,0,$len,true);

                // 各省份玩家分数总和
                $score = 0;
                foreach ($players as $player => $number) {
                    $score += $number;
                }
                $aggregation[$k] = $score;
            }

            // 省份排行排序
            array_multisort($aggregation,SORT_DESC,SORT_NUMERIC);

            // 玩家所在省份排名以及积分
            $playerkey  = Keys::getInstance()->getPlayerKey($uid);
            $province   = $redis->hGet($playerkey,'province');
            
            if(empty($province) || $province > 35) $province = 35;
            
            $keys   = array_keys($aggregation);
            $index = array_search(KV_PROVINCE[$province],$keys);

            return [
                "myName"    => KV_PROVINCE[$province],
                "myIndex"   => $index + 1,
                "worldData" => $aggregation,
            ];
        },'redis');

        return [
            [
                'name'  => $rankInfo['myName'],
                'index' => $rankInfo['myIndex'],
            ],
            $this->formatAllPlayersLeaderboardInProvince($rankInfo['worldData'],$len),
        ];
    }

    // 格式化输出(getWorldPlayersLeaderboard)
    public function formatWorldLeaderboard(array $arr):array
    {
        $leaderboard = [];
        $index = 1;
        foreach ($arr as $k => $v) 
        {
            // $playerkey = Keys::getInstance()->getPlayerKey($k);
            // $player = Redis::invoke(function (RedisClient $redis) use ($playerkey) {
            //     return $redis->hGetAll($playerkey);
            // },'redis');
            // //  缓存player为空
            // if(empty($player)){
            //     $player['name'] = 'default';
            //     $player['avatar'] = '';
            // }
            // if(empty($player['name'])){
            //     $player['name'] = 'default';
            // }
            // if(empty($player['avatar'])){
            //     $player['avatar'] = '';
            // }
            // $leaderboard[] = [
            //     'index'    => $index,
            //     'score'    => numToStr($v),
            //     'avatar'   => $player['avatar'],
            //     'name'     => $player['name'],
            // ];
            $playerData = new PlayerService($k);
            $leaderboard[]   = [
                'index'    => $index,
                'score'    => numToStr($v),
                'avatar'    => $playerData->getData('avatar') ? $playerData->getData('avatar') : '',
                'name'      => $playerData->getData('name') ? $playerData->getData('name') : 'default',
            ];
            $index++;
        }
        return $leaderboard;
    }

    // 格式化输出(getAllProvincesLeaderboard)
    public function formatAllProvincesLeaderboard(array $arr):array
    {
        $leaderboard = [];
        $index = 1;
        foreach ($arr as $k => $v) {
            $leaderboard[] = [
                'name'      => $k,
                'index'     => $index,
                'score'     => numToStr($v),
            ];
            $index++;
        }
        return $leaderboard;
    }

    // 格式化输出(getAllPlayersLeaderboardInProvince)
    public function formatAllPlayersLeaderboardInProvince(array $arr,$len):array
    {
        return Redis::invoke(function (RedisClient $redis) use ($arr,$len) {
            $leaderboard = [];
            $index = 1;
            // 循环各省份
            foreach ($arr as $k => $v) {
                $key        = Keys::getInstance()->getLeaderboardKey(VK_PROVINCE[$k]);
                $players    = $redis->zRevRange($key,0,$len,true);
                $playerinfo = [];
                // 循环各玩家
                foreach ($players as $player => $score) {
                    // $playerkey      = Keys::getInstance()->getPlayerKey($player);
                    // $info           = $redis->hGetAll($playerkey);
                    // //  缓存player为空
                    // if(empty($info)){
                    //     $info['name'] = 'default';
                    //     $info['avatar'] = '';
                    // }
                    // if(empty($info['name'])){
                    //     $info['name'] = 'default';
                    // }
                    // if(empty($info['avatar'])){
                    //     $info['avatar'] = '';
                    // }
                    // $playerinfo[]   = [
                    //     'uid'       => base64_encode($player),
                    //     'name'      => $info['name'],
                    //     'avatar'    => $info['avatar'],
                    //     'score'     => $score,
                    // ];
                    $playerData = new PlayerService($player);
                    $playerinfo[]   = [
                        'uid'       => base64_encode($player),
                        'name'      => $playerData->getData('name') ? $playerData->getData('name') : 'default',
                        'avatar'    => $playerData->getData('avatar') ? $playerData->getData('avatar') : '',
                        'score'     => $score,
                    ];
                }
                $leaderboard[] = [
                    'name'      => $k,
                    'index'     => $index,
                    'players'   => $playerinfo,
                ];
                $index++;
            }
            return $leaderboard;
        },'redis');
    }
}
