<?php
/**
 * Created by PhpStorm.
 * User: machuang
 * Date: 2019/12/27
 * Time: 18:24
 */

use light\web\Route;

Route::get('success', function() {
    echo "成功！";
});

Route::get('home', 'SiteController@test');

Route::get('(:all)', function($fu) {
    echo '未匹配到路由<br>'.$fu;
});

Route::dispatch();