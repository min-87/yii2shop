<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels(){
        return [
            'username' => 'Логин',
            'password' => 'Пароль',
            'rememberMe' => 'Запомнить',
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {//проверяем, не было ли ошибок
            $user = $this->getUser();//создаём объект user и вызываем метод getUser()

            if (!$user || !$user->validatePassword($this->password)) {//если не создан объект user или если валидация провалена
                $this->addError($attribute, 'Логин/пароль введены не верно');//выведем ошибку 'Логин/пароль введены не верно'
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {//валидирует данные согласно rules
            if($this->rememberMe){//если пользователь попросил запомнить
                $u = $this->getUser();//создадим объект пользователя
                $u->generateAuthKey();//обновляем свойство auth_key
                $u->save();//сохраняем auth_key в базу
            }
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);//если всё успешно, авторизуем пользователя. Если нужно записать в куки, записываем на месяц
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {//если пользователь не найден
            $this->_user = User::findByUsername($this->username);//пытаемся его найти и вернуть. Чтоб найти пользователя, вызываем метод findByUsername класса User, передавая имя пользователя, введённое из формы
        }

        return $this->_user;//вернём либо пользователя, либо false
    }
}
