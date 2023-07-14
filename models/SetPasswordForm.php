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
class SetPasswordForm extends Model
{

    public $username;
    public $password;
    public $password_konfirmasi;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // email and password are both required
            [['password_konfirmasi','password','username', 'password'], 'required','message'=>'{attribute} harus diisi'],
            [['username'],'string'],
            // password is validated by validatePassword()
            ['password_konfirmasi', 'validatePassword'],
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
        if($this->password != $this->password_konfirmasi)
        {
            $this->addError($attribute, 'Password konfirmasi tidak sesuai');
        }
    }

    
}
