<?php
/**
 * Created by PhpStorm.
 * User: machuang
 * Date: 2019/12/3
 * Time: 18:07
 */


return [
//    'class' => '\PDO',
    'class' => '\light\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=sf',
    'username' => 'jun',
    'password' => 'jun',
    'attributes' => [
        \PDO::ATTR_EMULATE_PREPARES => false,
        \PDO::ATTR_STRINGIFY_FETCHES => false,
    ],
];