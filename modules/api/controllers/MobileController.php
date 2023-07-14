<?php


namespace app\modules\api\controllers;

use yii\web\Controller;

class MobileController extends Controller
{
    public $enableCsrfValidation = false;

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

    public function actionStatus()
    {
        return [
            'version' => '1'
        ];
    }
}
