<?php
/**
 * Created by PhpStorm.
 * User: machuang
 * Date: 2019/12/27
 * Time: 18:24
 */

light\web\Route::get('success', function() {
    echo "成功！";
});

light\web\Route::get('home', 'SiteController@test');

light\web\Route::get('(:all)', function($fu) {
    echo '未匹配到路由<br>'.$fu;
});

light\web\Route::dispatch();