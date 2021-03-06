<?php
/**
 * Created by PhpStorm.
 * User: machuang
 * Date: 2019/12/3
 * Time: 15:39
 */
namespace light\web;


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

        // 设定错误和异常处理
//        register_shutdown_function('Think\Think::fatalError');
//        set_error_handler('Think\Think::appError');
//        set_exception_handler('Think\Think::appException');

    }


    /**
     * 自定义异常处理
     * @access public
     * @param mixed $e 异常对象
     */
    static public function appException($e) {
        $error = array();
        $error['message']   =   $e->getMessage();
        $trace              =   $e->getTrace();
        if('E'==$trace[0]['function']) {
            $error['file']  =   $trace[0]['file'];
            $error['line']  =   $trace[0]['line'];
        }else{
            $error['file']  =   $e->getFile();
            $error['line']  =   $e->getLine();
        }
        $error['trace']     =   $e->getTraceAsString();
        Log::record($error['message'],Log::ERR);
        // 发送404信息
        header('HTTP/1.1 404 Not Found');
        header('Status:404 Not Found');
        self::halt($error);
    }


    /**
     * 自定义错误处理
     * @access public
     * @param int $errno 错误类型
     * @param string $errstr 错误信息
     * @param string $errfile 错误文件
     * @param int $errline 错误行数
     * @return void
     */
    static public function appError($errno, $errstr, $errfile, $errline) {
        switch ($errno) {
            case E_ERROR:
            case E_PARSE:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
                ob_end_clean();
                $errorStr = "$errstr ".$errfile." 第 $errline 行.";
                if(C('LOG_RECORD')) Log::write("[$errno] ".$errorStr,Log::ERR);
                self::halt($errorStr);
                break;
            default:
                $errorStr = "[$errno] $errstr ".$errfile." 第 $errline 行.";
                self::trace($errorStr,'','NOTIC');
                break;
        }
    }


    // 致命错误捕获
    static public function fatalError() {
        Log::save();
        if ($e = error_get_last()) {
            switch($e['type']){
                case E_ERROR:
                case E_PARSE:
                case E_CORE_ERROR:
                case E_COMPILE_ERROR:
                case E_USER_ERROR:
                    ob_end_clean();
                    self::halt($e);
                    break;
            }
        }
    }



    /**
     * 错误输出
     * @param mixed $error 错误
     * @return void
     */
    static public function halt($error) {
        $e = array();
        if (APP_DEBUG || IS_CLI) {
            //调试模式下输出错误信息
            if (!is_array($error)) {
                $trace          = debug_backtrace();
                $e['message']   = $error;
                $e['file']      = $trace[0]['file'];
                $e['line']      = $trace[0]['line'];
                ob_start();
                debug_print_backtrace();
                $e['trace']     = ob_get_clean();
            } else {
                $e              = $error;
            }
            if(IS_CLI){
                exit(iconv('UTF-8','gbk',$e['message']).PHP_EOL.'FILE: '.$e['file'].'('.$e['line'].')'.PHP_EOL.$e['trace']);
            }
        } else {
            //否则定向到错误页面
            $error_page         = C('ERROR_PAGE');
            if (!empty($error_page)) {
                redirect($error_page);
            } else {
                $message        = is_array($error) ? $error['message'] : $error;
                $e['message']   = C('SHOW_ERROR_MSG')? $message : C('ERROR_MESSAGE');
            }
        }
        // 包含异常页面模板
        $exceptionFile =  C('TMPL_EXCEPTION_FILE',null,THINK_PATH.'Tpl/think_exception.tpl');
        include $exceptionFile;
        exit;
    }

    /**
     * 添加和获取页面Trace记录
     * @param string $value 变量
     * @param string $label 标签
     * @param string $level 日志级别(或者页面Trace的选项卡)
     * @param boolean $record 是否记录日志
     * @return void|array
     */
    static public function trace($value='[think]',$label='',$level='DEBUG',$record=false) {
        static $_trace =  array();
        if('[think]' === $value){ // 获取trace信息
            return $_trace;
        }else{
            $info   =   ($label?$label.':':'').print_r($value,true);
            $level  =   strtoupper($level);

            if((defined('IS_AJAX') && IS_AJAX) || !C('SHOW_PAGE_TRACE')  || $record) {
                Log::record($info,$level,$record);
            }else{
                if(!isset($_trace[$level]) || count($_trace[$level])>C('TRACE_MAX_RECORD')) {
                    $_trace[$level] =   array();
                }
                $_trace[$level][]   =   $info;
            }
        }
    }



    /**
     * @return mixed
     * author:  Ma
     * 路由处理方式
     */
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


    /**
     * author:  Ma
     * 设置是否在前台展示报错
     */
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