<?php


namespace app\modules\api\controllers;

use app\models\Eselon;
use Yii;
use yii\web\Controller;

class EselonController extends Controller
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

    public function actionView($id)
    {
        $model = Eselon::findOne($id);

        if($model === null) {
            Yii::$app->response->statusCode = 403;
            return [
                'message' => 'Eselon tidak ditemukan',
                'status' => 400,
            ];
        }

        return [
            'data' => $model,
            'status' => 200
        ];
    }
}

