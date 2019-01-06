<?php
/**
 * Created by PhpStorm.
 * User: Andrey
 * Date: 07.05.2016
 * Time: 10:28
 */

namespace app\models;
use yii\db\ActiveRecord;


class Product extends ActiveRecord{
//подсказать, с какой таблицей будет связана данная модель
    public static function tableName(){
        return 'product';
    }

    public function getCategory(){
        return $this->hasOne(Category::className(), ['id' => 'category_id']);//один продукт может содержать только одну категорию($this->hasOne), 1-ое значение из связываемой таблицы(category_id), 2-ое - из текущей(id)
    }

} 