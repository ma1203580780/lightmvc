<?php
/**
 * Created by PhpStorm.
 * User: machuang
 * Date: 2019/12/3
 * Time: 19:34
 */
return [
    'class' => 'sf\cache\RedisCache',
    'redis' => [
        'host' => 'localhost',
        'port' => 6379,
        'database' => 0,
        // 'password' =>'jun',
        // 'options' => [Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP],
    ]
];