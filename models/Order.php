<?php

namespace app\models;

use yii\db\ActiveRecord;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
/**
 * This is the model class for table "order".
 *
 * @property string $id
 * @property string $created_at
 * @property string $updated_at
 * @property int $qty
 * @property double $sum
 * @property string $status
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $address
 */
class Order extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order';
    }
    /**
     * Связь с таблицей OrderItems
     */
    public function getOrderItems(){
        return $this->hasMany(OrderItems::className(), ['order_id' => 'id']);//1 заказ может иметь много заказанных товаров внутри себя
    }
//возвращает массив с конфигурацией поведения
    public function behaviors(){
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],//перед вставкой новой записи заполняет объявленные поля('created_at', 'updated_at') меткой времени
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],//перед обновлением записи заполняет объявленные поля('updated_at') меткой времени
                ],
                // если вместо метки времени UNIX используется datetime:
                'value' => new Expression('NOW()'),//метка времени будет переведена к понятному нам формату даты
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email', 'phone', 'address'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['qty'], 'integer'],
            [['sum'], 'number'],
            [['status'], 'boolean'],
            [['name', 'email', 'phone', 'address'], 'string', 'max' => 255],
        ];
    }

    /**
     поля для нашей формы
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Имя',
            'email' => 'E-mail',
            'phone' => 'Телефон',
            'address' => 'Адрес',
        ];
    }
}
