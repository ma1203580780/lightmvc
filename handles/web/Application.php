<?php
/**
 * Created by PhpStorm.
 * User: machuang
 * Date: 2019/12/3
 * Time: 15:39
 */
namespace light\web;

/**
 * Application is the base class for all application classes.
 * @author Harry Sun <sunguangjun@126.com>
 */
class Application extends \light\base\Application
{
    /**
     * Handles the specified request.
     * @return Response the resulting response
     */
    public function handleRequest()
    {

        $router = $_GET['r'];
        list($controllerName, $actionName) = explode('/', $router);
        $ucController = ucfirst($controllerName);
        $controllerNameAll = $this->controllerNamespace . '\\' . $ucController . 'Controller';
        $controller = new $controllerNameAll();
        $controller->id = $controllerName;
        $controller->action = $actionName;
        return call_user_func([$controller, 'action'. ucfirst($actionName)]);
    }


    /**
     * author:  Ma
     * 将对象当作函数来使用的时候，会自动调用该方法。
     */
    public function __invoke($className){

        //单例模式  实例化这个函数
        return new $className;


    }

}