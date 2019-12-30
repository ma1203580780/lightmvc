<?php
/**
 * Created by PhpStorm.
 * User: machuang
 * Date: 2019/12/3
 * Time: 17:30
 */
namespace app\models;

use light\db\Model;

/**
 * User model
 * @property integer $id
 * @property string $name
 * @property integer $age
 */
class User extends Model
{
    public static function tableName()
    {
        return 'user';
    }
}