<?php
/**
 * Created by PhpStorm.
 * User: machuang
 * Date: 2019/12/3
 * Time: 17:15
 */
namespace light\db;

interface ModelInterface
{
    public static function tableName();

    public static function primaryKey();

    public static function findOne($condition);

    public static function findAll($condition);

    public static function updateAll($condition, $attributes);

    public static function deleteAll($condition);

    public function insert();

    public function update();

    public function delete();
}