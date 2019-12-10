<?php
/**
 * Created by PhpStorm.
 * User: machuang
 * Date: 2019/12/3
 * Time: 18:24
 *
 * Light is a helper class serving common framework functionalities.
 */

class Light
{
    /**
     * Creates a new object using the given configuration.
     * You may view this method as an enhanced version of the `new` operator.
     * @param string $name the object name
     */
    public static function createObject($name)
    {
        $config = require(SF_PATH . "/config/$name.php");
        // create instance
        $instance = new $config['class']();
        unset($config['class']);
        // add attributes
        foreach ($config as $key => $value) {
            $instance->$key = $value;
        }
        $instance->init();
        return $instance;
    }


}