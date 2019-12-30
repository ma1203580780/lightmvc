<?php
/**
 * Created by PhpStorm.
 * User: machuang
 * Date: 2019/12/3
 * Time: 11:25
 */

define('APP_PATH', dirname(__DIR__));

define('APP_DEBUG', true);

define("ROUTE_MODE",3);


require_once(APP_PATH . '/vendor/autoload.php');
require_once(APP_PATH . '/handles/Light.php');

(new light\web\Application())->run();//核心执行

