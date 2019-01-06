<?php
/**
 * Created by PhpStorm.
 * User: Andrey
 * Date: 14.05.2016
 * Time: 10:40
 */

namespace app\models;
use yii\db\ActiveRecord;

class Cart extends ActiveRecord{
//метод добавления в корзину
    public function addToCart($product, $qty = 1){//принимает продукт и его к-ство, по умолчанию 1
        if(isset($_SESSION['cart'][$product->id])){//если товар уже есть в корзине,
            $_SESSION['cart'][$product->id]['qty'] += $qty;//то просто приплюсовываем к-ство
        }else{//если его нет
            $_SESSION['cart'][$product->id] = [//тогда мы должны такой элемент создать
                'qty' => $qty,//к-ство
                'name' => $product->name,//название
                'price' => $product->price,//цена товара
                'img' => $product->img//картинка
            ];
        }
        $_SESSION['cart.qty'] = isset($_SESSION['cart.qty']) ? $_SESSION['cart.qty'] + $qty : $qty;//если уже есть общее к-ство, тогда его возьмём и прибавим к нему то к-ство, которое пришло параметром. Если не существует, тогда сюда положим это к-ство
        $_SESSION['cart.sum'] = isset($_SESSION['cart.sum']) ? $_SESSION['cart.sum'] + $qty * $product->price : $qty * $product->price;//если уже есть общая сумма, тогда возьмём к-ство и умножим на цену и прибавим к общей сумме. Если не существует, тогда ложим сюда к-ство, умноженное на цену
    }
//метод пересчёта корзины
    public function recalc($id){//принимает id товара
        if(!isset($_SESSION['cart'][$id])) return false;//проверяем, существует ли в сессии такой элемент. Если его нет, вернём false
        //а если существует, мы должны уменьшить итоговое к-ство на то, которое удаляем из корзины
        $qtyMinus = $_SESSION['cart'][$id]['qty'];//возьмём текущий удаляемый элемент($id) и его к-ство(qty)
        $sumMinus = $_SESSION['cart'][$id]['qty'] * $_SESSION['cart'][$id]['price'];//возьмём текущиее к-ство и умножаем на цену данного товара. И именно полученную сумму будем отнимать из итоговой
        $_SESSION['cart.qty'] -= $qtyMinus;//пересчитываем итоговое к-ство
        $_SESSION['cart.sum'] -= $sumMinus;//пересчитываем итоговую сумму
        unset($_SESSION['cart'][$id]);//удалить текущий товар
    }

} 