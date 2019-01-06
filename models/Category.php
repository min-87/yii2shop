<?php
/**
 * Created by PhpStorm.
 * User: Andrey
 * Date: 07.05.2016
 * Time: 10:28
 */

namespace app\models;
use yii\db\ActiveRecord;


class Category extends ActiveRecord{
//подсказать, с какой таблицей будет связана данная модель
    public static function tableName(){
        return 'category';
    }
//связь с таблицей продуктов
    public function getProducts(){
        return $this->hasMany(Product::className(), ['category_id' => 'id']);//категория может содержать много товаров($this->hasMany), 1-ое значение из связываемой таблицы(category_id), 2-ое - из текущей(id)
    }

} 