<?php
/**
 * Created by PhpStorm.
 * User: machuang
 * Date: 2019/12/3
 * Time: 16:20
 */

namespace light\web;

/**
 * Controller is the base class for classes containing controller logic.
 * @author Harry Sun <sunguangjun@126.com>
 */
class Controller extends \light\base\Controller
{
    /**
     * Renders a view
     * @param string $view the view name.
     * @param array $params the parameters (name-value pairs) that should be made available in the view.
     */
    public function render($view, $params = [])
    {
        extract($params);
        return require '../views/' . $view . '.php';
    }

    /**
     * Convert a array to json string
     * @param string $data
     */
    public function toJson($data)
    {
        if (is_string($data)) {
            return $data;
        }
        return json_encode($data);
    }

    public function __call($name, $arguments)
    {
        echo __CLASS__.'控制器的'.$name.'方法不存在';
    }
}