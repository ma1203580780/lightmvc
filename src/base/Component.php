<?php
/**
 * Created by PhpStorm.
 * User: machuang
 * Date: 2019/12/3
 * Time: 19:33
 */
namespace sf\base;

/**
 * Component is the base class for most sf classes.
 * @author Harry Sun <sunguangjun@126.com>
 */
class Component
{
    /**
     * Initializes the component.
     * This method is invoked at the end of the constructor after the object is initialized with the
     * given configuration.
     */
    public function init()
    {
    }
}