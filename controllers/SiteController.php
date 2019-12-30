<?php
/**
 * Created by PhpStorm.
 * User: machuang
 * Date: 2019/12/3
 * Time: 15:13
 */

namespace app\controllers;

use Light;
use app\models\User;
use light\web\Controller;

class SiteController extends Controller
{
    public function test()
    {
        echo 'success!';
    }

    public function actionView()
    {
        $this->render('site/view', ['body' => 'Test body information']);
    }

    public function actionApi()
    {
        $data = ['first' => 'awesome-php-zh_CN', 'second' => 'simple-framework'];
        echo $this->toJson($data);
    }

    public function actionDb()
    {
        $user = User::findOne(['age' => 20, 'name' => 'harry']);
        $data = [
            'first' => 'awesome-php-zh_CN',
            'second' => 'simple-framework',
            'user' => $user
        ];
        echo $this->toJson($data);
    }


    public function actionCache()
    {
        $cache = Light::createObject('cache');
        $cache->set('test', '我就是测试一下缓存组件');
        $result = $cache->get('test');
        $cache->flush();
        echo $result;
    }


}
