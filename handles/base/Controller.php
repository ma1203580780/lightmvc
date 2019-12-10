<?php
/**
 * Created by PhpStorm.
 * User: machuang
 * Date: 2019/12/3
 * Time: 16:20
 */
namespace light\base;

/**
 * Class Controller
 * @package Light\base
 */
class Controller
{
    /**
     * @var string the ID of this controller.
     */
    public $id;
    /**
     * @var Action the action that is currently being executed.
     */
    public $action;
}