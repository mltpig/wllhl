<?php

use EasySwoole\Log\LoggerInterface;

return [
    'SERVER_NAME' => "EasySwoole",
    'MAIN_SERVER' => [
        'LISTEN_ADDRESS' => '0.0.0.0',
        'PORT' => 8053,
        'SERVER_TYPE' => EASYSWOOLE_WEB_SERVER, //可选为 EASYSWOOLE_SERVER  EASYSWOOLE_WEB_SERVER EASYSWOOLE_WEB_SOCKET_SERVER
        'SOCK_TYPE' => SWOOLE_TCP,
        'RUN_MODEL' => SWOOLE_PROCESS,
        'SETTING' => [
            'worker_num' => 8,
            'reload_async' => true,
            'max_wait_time' => 3
        ],
        'TASK' => [
            'workerNum' => 4,
            'maxRunningNum' => 128,
            'timeout' => 15
        ]
    ],
    "LOG" => [
        'dir' => null,
        'level' => LoggerInterface::LOG_LEVEL_DEBUG,
        'handler' => null,
        'logConsole' => true,
        'displayConsole' => true,
        'ignoreCategory' => []
    ],
    'TEMP_DIR' => null,
    'MYSQL' => [
        'host'              => '172.16.0.11',
        'port'              => '9819',
        'user'              => 'wllhl_wx',
        'timeout'           => '5',
        'charset'           => 'utf8mb4',
        'password'          => 'ajHx8s2mzezHaAFbPmNzMAeihtpFkNJ4KPK8CaAx3cW84WeSp5P5cJAx72383iLX',
        'database'          => 'wllhl_wx',
        'intervalCheckTime' => 15 * 1000, // 设置 连接池定时器执行频率
        'maxIdleTime'       => 10, // 设置 连接池对象最大闲置时间 (秒)
        'maxObjectNum'      => 20, // 设置 连接池最大数量
        'minObjectNum'      => 5, // 设置 连接池最小数量
        'getObjectTimeout'  => 5.0, // 设置 获取连接池的超时时间
        'loadAverageTime'   => 0.001, // 设置 负载阈值
    ],
    'REDIS' => [
        'host'              => '172.16.0.11',
        'port'              => '9101',
        'auth'              => '91013QgoMX86oD2KcZm2Q4rX15MqFD9GArly0yc2vrWwsK4nyk3EUwpofAUsnIRtcex3RnoSLGUuNEGCSLXMT4CdPIQ6sEQoalBxa2yPjN6oLGuidRSvMMifqYl0ampn1tHRO4DReQ1Uw7VkkThu19fiwR',
        'timeout'           => 3.0, // Redis 操作超时时间
        'reconnectTimes'    => 3, // Redis 自动重连次数
        'db'                => 0, // Redis 库
        'serialize'         => \EasySwoole\Redis\Config\RedisConfig::SERIALIZE_NONE, // 序列化类型，默认不序列化
        'packageMaxLength'  => 1024 * 1024 * 10, // 允许操作的最大数据
        'intervalCheckTime' => 15 * 1000, // 设置 连接池定时器执行频率
        'maxIdleTime'       => 10, // 设置 连接池对象最大闲置时间 (秒)
        'maxObjectNum'      => 20, // 设置 连接池最大数量
        'minObjectNum'      => 5, // 设置 连接池最小数量
        'getObjectTimeout'  => 3.0, // 设置 获取连接池的超时时间
        'loadAverageTime'   => 0.001, // 设置 负载阈值
    ],
];
