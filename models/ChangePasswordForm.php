<?php

namespace app\models;

use kartik\password\StrengthValidator;
use Yii;
use yii\base\Model;
use yii\httpclient\Client;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class ChangePasswordForm extends Model
{
    public $username;
    public $password_lama;
    public $password_baru;
    public $password_baru_konfirmasi;

    public $validateSso = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // email and password are both required
            [['password_lama', 'password_baru', 'password_baru_konfirmasi'], 'required', 'message' => '{attribute} harus diisi'],
            // password is validated by validatePassword()
            [['password_lama'], 'validateLamaSama', 'when' => function() {
                return !$this->validateSso;
            }],
            [['password_lama'], 'validateSsoLamaSama', 'when' => function() {
                return $this->validateSso;
            }],
            [['password_baru_konfirmasi'], 'validateBaruSama'],
            [['password_baru'], StrengthValidator::class, 'preset'=>'normal', 'userAttribute'=>'username'],
            [['username'], 'safe'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateLamaSama($attribute, $params)
    {
        $user = User::findBySession();

        if (!$user->validatePassword($this->password_lama)) {
            $this->addError($attribute, 'Password lama tidak sesuai');
        }
    }

    public function validateBaruSama($attribute, $params)
    {
        if ($this->password_baru != $this->password_baru_konfirmasi) {
            $this->addError($attribute, 'Password baru konfirmasi tidak sesuai');
        }
    }

    public function validateSsoLamaSama($attribute)
    {
        $user = User::findBySession();

        if($user->id_user_role === UserRole::PEGAWAI) {
            if(!$this->verifyPasswordSso()) {
                $this->addError($attribute, 'Password lama tidak sesuai');
            }
        } else {
            if (!$user->validatePassword($this->password_lama)) {
                $this->addError($attribute, 'Password lama tidak sesuai');
            }
        }
    }

    public function verifyPasswordSso()
    {
        $url = Yii::$app->params['url_sso'];
        $params = '/api/user/verify-password';

        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl($url.$params)
            ->addData([
                'username' => @Yii::$app->user->identity->username,
                'password' => $this->password_lama,
            ])
            ->send();

        if($response->getStatusCode() != 200) {
            return false;
        }

        return true;
    }

}
