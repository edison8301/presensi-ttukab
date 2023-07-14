<?php


namespace app\modules\api2\controllers;


use app\modules\api2\models\InstansiPegawaiSkp;
use yii\web\Controller;

class KegiatanHarianTambahanController extends Controller
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

    public function actionIndex()
    {
        $data = \app\modules\api2\models\KegiatanHarianTambahan::restList();

        return [
            'status' => 'success',
            'data' => $data
        ];
    }
}
