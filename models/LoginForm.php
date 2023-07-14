<?php
namespace app\models;

use Yii;
use yii\base\Model;
use yii\httpclient\Client;

/**
 * Login form
 */
class LoginForm extends Model
{
    const SCENARIO_BACKEND = 'backend';

    public $username;
    public $password;
    public $tahun;
    public $rememberMe = false;
    public $validateSso = false;

    private $_user;

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors[] = [
            'class' => '\giannisdag\yii2CheckLoginAttempts\behaviors\LoginAttemptBehavior',

            // Amount of attempts in the given time period
            'attempts' => 5,

            // the duration, in seconds, for a regular failure to be stored for
            // resets on new failure
            'duration' => 300,

            // the duration, in seconds, to disable login after exceeding `attemps`
            'disableDuration' => 300,

            // the attribute used as the key in the database
            // and add errors to
            'usernameAttribute' => 'username',

            // the attribute to check for errors
            'passwordAttribute' => 'password',

            // the validation message to return to `usernameAttribute`
            'message' => Yii::t('app', 'Anda telah salah beberapa kali. Silahakan tunggu 5 menit sebelum dapat mencoba lagi'),
        ];

        return $behaviors;
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();

        return $scenarios;

    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password', 'tahun'], 'required'],
            [['tahun'], 'number', 'min' => 2018, 'max' => date('Y')],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword', 'when' => function () {
                return !$this->validateSso;
            }],
            ['password', 'validatePasswordSso', 'when' => function () {
                return $this->isPegawai() AND $this->validateSso;
            }]
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
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {

            Yii::$app->session->set('tahun',$this->tahun);
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);

        } else {
            return false;
        }
    }

    public function validatePasswordSso($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $url = Yii::$app->params['url_sso'];
            $params = '/api/auth/login';

            $client = new Client();
            $response = $client->createRequest()
                ->setMethod('POST')
                ->setUrl($url.$params)
                ->addData([
                    'username' => $this->username,
                    'password' => $this->password,
                ])
                ->send();

            $responseJson = json_decode($response->content);

            if ($response->getStatusCode() != 200 AND @$responseJson->status != 'success') {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }

    public function setIdPegawaiSession()
    {
        $pegawai = Pegawai::findOne(['kode_pegawai' => $this->_user->kode_pegawai, 'tahun' => User::getTahun()]);
        Yii::$app->session->set('id_pegawai', $pegawai->id);
        return;
    }

    public function isPegawai() {
        $user = $this->getUser();
        return @$user->id_user_role === UserRole::PEGAWAI;
    }
}
