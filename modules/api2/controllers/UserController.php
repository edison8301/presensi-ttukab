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

class UserController extends Controller
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
        if($action->id == 'change-password') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    public function actionChangePassword()
    {
        $params = Yii::$app->request->post();

        $model = User::findOne(['username'=>$params['username']]);

        if ($model===null) {
            return [
                'change_password' => false,
                'message' => 'User tidak ditemukan'
            ];
        }

        if (Yii::$app->getSecurity()->validatePassword($params['password_lama'],$model->password) == false)
        {
            return [
                'change_password' => false,
                'message' => 'Password lama tidak sesuai.'
            ];
        }

        $model->password = Yii::$app->getSecurity()->generatePasswordHash($params['password_baru']);
        if($model->save()) {
            return [
                'change_password'=>true,
                'message' => ''
            ];
        }

        return [
            'change_password'=>false,
            'message' => 'Terjadi Kesalahan. Silahkan coba lagi nanti'
        ];
    }
}
