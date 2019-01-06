<?php
/**
 * Created by PhpStorm.
 * User: Andrey
 * Date: 08.05.2016
 * Time: 10:00
 */

namespace app\controllers;
use app\models\Category;
use app\models\Product;
use Yii;
use yii\data\Pagination;

class CategoryController extends AppController{
//действие по умолчанию
    public function actionIndex(){
        $hits = Product::find()->where(['hit' => '1'])->limit(6)->all();//выбираем 6 продуктов, где поле 'hit' = '1'(enum-текстовое поле)
        $this->setMeta('E-SHOPPER');
        return $this->render('index', compact('hits'));
    }
//страница товаров нужной категории
    public function actionView($id){
        //        $id = Yii::$app->request->get('id');//получаем id категории из массива GET в переменную id
        $category = Category::findOne($id);//получаем категорию по id
        if(empty($category))//если не получили никаких данных о категории
            throw new \yii\web\HttpException(404, 'Такой категории нет');//выбрасываем исключение
        //        $products = Product::find()->where(['category_id' => $id])->all();//получаем продукты по id категории
        $query = Product::find()->where(['category_id' => $id]);//получаем объект запроса, чтоб посчитать к-ство записей, но сам запрос выполнять не будем
        $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => 3, 'forcePageParam' => false, 'pageSizeParam' => false]);//создаём объект класса Pagination. totalCount - общее к-ство записей, котрое будет вытащено из запроса, pageSize - к-ство записей на страницу, forcePageParam-отвечает за ЧПУ ссылки, pageSizeParam - отвечает за GET параметр perpage
        $products = $query->offset($pages->offset)->limit($pages->limit)->all();//выполняем сам запрос. offset - с какой записи начинать выборку, limit - сколько таких записей взять

        $this->setMeta('E-SHOPPER | ' . $category->name, $category->keywords, $category->description);
        return $this->render('view', compact('products', 'pages','category'));
    }
//страница поиска
    public function actionSearch(){
        $q = trim(Yii::$app->request->get('q'));//получаем запрос
        $this->setMeta('E-SHOPPER | Поиск: ' . $q);
        if(!$q)//если переменная $q вернёт ложь
            return $this->render('search');//просто возвращаем страницу с видом
        $query = Product::find()->where(['like', 'name', $q]);//ищем продукты по данному запросу
        $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => 3, 'forcePageParam' => false, 'pageSizeParam' => false]);
        $products = $query->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('search', compact('products', 'pages', 'q'));
    }

}