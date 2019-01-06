<?php
/**
 * Created by PhpStorm.
 * User: Andrey
 * Date: 09.05.2016
 * Time: 10:50
 */

namespace app\controllers;
use app\models\Category;
use app\models\Product;
use Yii;

class ProductController extends AppController{
//страница карточки товара
    public function actionView($id){//принимаем id продукта
        //        $id = Yii::$app->request->get('id');//получаем id продукта из массива GET
        $product = Product::findOne($id);//получаем из базы всю информацию по данному товару
        if(empty($product))//если не получили никаких данных о продукте
            throw new \yii\web\HttpException(404, 'Такого товара нет');//выбрасываем исключение
//        $product = Product::find()->with('category')->where(['id' => $id])->limit(1)->one();
        $hits = Product::find()->where(['hit' => '1'])->limit(6)->all();//получаем 6 продуктов из таблицы Product, где hit = 1
        $this->setMeta('E-SHOPPER | ' . $product->name, $product->keywords, $product->description);
        return $this->render('view', compact('product', 'hits'));
    }

} 