<?php
/**
 * Created by PhpStorm.
 * User: machuang
 * Date: 2019/12/3
 * Time: 15:13
 */

namespace app\controllers;

    use Sf;
    use app\models\User;
    use sf\web\Controller;

class SiteController extends Controller
{
    public function actionTest()
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
        $cache = Sf::createObject('cache');
        $cache->set('test', '我就是测试一下缓存组件');
        $result = $cache->get('test');
        $cache->flush();
        echo $result;
    }


}
