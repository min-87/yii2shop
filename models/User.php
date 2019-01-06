<?php

namespace app\models;
use yii\db\ActiveRecord;

class User extends ActiveRecord implements \yii\web\IdentityInterface
{

    public static function tableName(){
        return 'user';
    }
    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);//возвращает найденного пользователя по его id
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        //        return static::findOne(['access_token' => $token]);
    }

    /**
     ищем пользователя по его логину
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);//'username' должно быть равно тому, что пользователь ввёл через форму
    }

    /**
     * получаем id пользователя
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     сравнивает пароль, который хранится в базе с тем, что набрал пользователь
     */
    public function validatePassword($password)
    {
        //        return $this->password === $password;
        return \Yii::$app->security->validatePassword($password, $this->password);//$password - то, что ввёл пользователь. $this->password - то, что есть в базе
    }
//генерация случайной строки при авторизации для поля auth_key в базе
    public function generateAuthKey(){
        $this->auth_key = \Yii::$app->security->generateRandomString();
    }
}
