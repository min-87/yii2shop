<?php
/**
 * Created by PhpStorm.
 * User: Andrey
 * Date: 14.05.2016
 * Time: 10:37
 */

namespace app\controllers;
use app\models\Product;
use app\models\Cart;
use app\models\Order;
use app\models\OrderItems;
use Yii;

/*Array
(
    [1] => Array
    (
        [qty] => QTY
        [name] => NAME
        [price] => PRICE
        [img] => IMG
    )
    [10] => Array
    (
        [qty] => QTY
        [name] => NAME
        [price] => PRICE
        [img] => IMG
    )
)
    [qty] => QTY,
    [sum] => SUM
);*/

class CartController extends AppController{
//добавление в корзину
    public function actionAdd(){
        $id = Yii::$app->request->get('id');//в переменную $id получаем нужные нам данные из массива GET
        $qty = (int)Yii::$app->request->get('qty');//в переменную $qty получаем нужные нам данные из массива GET, приводим к целому числу, так как мы ожидаем число
        $qty = !$qty ? 1 : $qty;//проверяем, если $qty нет(!$qty), тогда присвоим 1 по умолчанию, в противном случае положим то, что ввёл пользователь
        $product = Product::findOne($id);//в переменную $product получим из модели Product инфо о товаре по id
        if(empty($product)) return false;//если пуст $product, то просто завершим выполнение кода
        $session =Yii::$app->session;//стартуем сессию. Помещаем объект сессии в переменную $session
        $session->open();//открыть сессию
        $cart = new Cart();//создаём объект модели
        $cart->addToCart($product, $qty);//вызываем метод addToCart, передавая ему продукт и к-ство
        if( !Yii::$app->request->isAjax ){//если запрос идёт не методом Ajax
            return $this->redirect(Yii::$app->request->referrer);//просто сделаем редирект на ту стр, с которой пришёл пользователь
        }
        $this->layout = false;//уберём шаблон
        return $this->render('cart-modal', compact('session'));
    }
//очистка корзины
    public function actionClear(){
        $session =Yii::$app->session;//стартуем сессию. Помещаем объект сессии в переменную $session
        $session->open();//открыть сессию
        $session->remove('cart');//удаляем по ключу cart все товары из корзины
        $session->remove('cart.qty');//удаляем к-ство
        $session->remove('cart.sum');//удаляем сумму
        $this->layout = false;//уберём шаблон
        return $this->render('cart-modal', compact('session'));
    }
//удаление товара
    public function actionDelItem(){
        $id = Yii::$app->request->get('id');//получаем id товара
        $session =Yii::$app->session;//стартуем сессию. Помещаем объект сессии в переменную $session
        $session->open();//открыть сессию
        $cart = new Cart();//создаём объект модели
        $cart->recalc($id);//вызываем метод recalc и передаём ему полученный id
        $this->layout = false;//уберём шаблон
        return $this->render('cart-modal', compact('session'));
    }
//показать корзину
    public function actionShow(){
        $session =Yii::$app->session;//стартуем сессию. Помещаем объект сессии в переменную $session
        $session->open();//открыть сессию
        $this->layout = false;//уберём шаблон
        return $this->render('cart-modal', compact('session'));
    }
//страница оформления заказа
    public function actionView(){
        $session =Yii::$app->session;//стартуем сессию. Помещаем объект сессии в переменную $session
        $session->open();//открыть сессию
        $this->setMeta('Корзина');//заголовок страницы
        $order = new Order();//создаём модель нашего заказа
        if( $order->load(Yii::$app->request->post()) ){//проверим, принимаем ли мы данные(имя, почта, тел и адрес)
            $order->qty = $session['cart.qty'];//принимаем к-ство
            $order->sum = $session['cart.sum'];//принимаем сумму
            if($order->save()){//если заказ сохранён
                $this->saveOrderItems($session['cart'], $order->id);//передаём корзину($session['cart'])  и id заказа
                Yii::$app->session->setFlash('success', 'Ваш заказ принят. Менеджер вскоре свяжется с Вами.');//флешсообщение в случае успеха
                Yii::$app->mailer->compose('order', ['session' => $session])//метод отправки почты. Параметром принимает вид(order) и корзину
                    ->setFrom(['username@mail.ru' => 'yii2.loc'])//с какого email отправляется данная почта(то же, что и в config/web). yii2.loc - то, что увидит пользователь в поле "от"
                    ->setTo($order->email)//куда будем отправлять данное письмо
                    ->setSubject('Заказ')//тема письма
                    ->send();//отправляем письмо
                $session->remove('cart');//очищаем корзину
                $session->remove('cart.qty');
                $session->remove('cart.sum');
                return $this->refresh();//перезагрузить страницу
            }else{
                Yii::$app->session->setFlash('error', 'Ошибка оформления заказа');//флешсообщение в случае ошибки
            }
        }
        return $this->render('view', compact('session', 'order'));//session - корзина
    }
//сохранение  заказа
    protected function saveOrderItems($items, $order_id){//принимает корзину($items) и id заказа
        foreach($items as $id => $item){//пройдёмся по массиву корзины и будем получать id товара и всю информацию о заказе
            $order_items = new OrderItems();//создадим объект модели
            $order_items->order_id = $order_id;//id заказа
            $order_items->product_id = $id;//id товара
            $order_items->name = $item['name'];
            $order_items->price = $item['price'];
            $order_items->qty_item = $item['qty'];
            $order_items->sum_item = $item['qty'] * $item['price'];
            $order_items->save();//сохраняем запись
        }
    }

} 