<?php
/**
 * Created by PhpStorm.
 * User: machuang
 * Date: 2019/12/3
 * Time: 11:25
 */

define('SF_PATH', dirname(__DIR__));
require_once(SF_PATH . '/vendor/autoload.php');
require_once(SF_PATH . '/handles/Light.php');

$application = new light\web\Application();
$application->run();

