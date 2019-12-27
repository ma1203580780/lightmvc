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
        $this->router();
        $this->setReporting();

    }


    public function router(){
        if(ROUTE_MODE == 1){
            //get传参方式解析方式
            $router = $_GET['r'];
            list($controllerName, $actionName) = explode('/', $router);
            $ucController = ucfirst($controllerName);
            $controllerNameAll = $this->controllerNamespace . '\\' . $ucController . 'Controller';
            $controller = new $controllerNameAll();
            $controller->id = $controllerName;
            $controller->action = $actionName;
            return call_user_func([$controller,  ucfirst($actionName)]);
        }elseif (ROUTE_MODE == 2){
            //斜杠分割路由的解析方式
            $controllerName = 'Site';
            $actionName = 'actionIndex';
            $param = array();
            $url = $_SERVER['REQUEST_URI'];
            $position = strpos($url, '?');
            $url = $position === false ? $url : substr($url, 0, $position);
            $url = trim($url, '/');
            if ($url) {
                $urlArray = explode('/', $url);
                $urlArray = array_filter($urlArray);
                $controllerName = ucfirst($urlArray[0]);
                array_shift($urlArray);
                $actionName = $urlArray ? $urlArray[0] : $actionName;
                array_shift($urlArray);
                $param = $urlArray ? $urlArray : array();
            }

            $controller = $this->controllerNamespace . '\\' . $controllerName . 'Controller';
            if (!class_exists($controller)) {
                exit($controller . '控制器不存在');
            }
            if (!method_exists($controller, $actionName)) {
                exit($actionName . '方法不存在');
            }
            $dispatch = new $controller($controllerName, $actionName);
            call_user_func_array(array($dispatch, $actionName), $param);
        }elseif (ROUTE_MODE == 3){
            //有专门的路由文件的解析方式

            require APP_PATH."/route.php";

        }

    }


    public function setReporting()
    {
//        if (APP_DEBUG === true) {
//            error_reporting(E_ALL);
//            ini_set('display_errors','On');
//        } else {
//            error_reporting(E_ALL);
//            ini_set('display_errors','Off');
//            ini_set('log_errors', 'On');
//        }
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