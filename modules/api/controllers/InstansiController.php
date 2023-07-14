<?php

namespace app\modules\api\controllers;

use app\models\Instansi;
use app\models\Pegawai;
use Yii;
use yii\web\Controller;

header('Access-Control-Allow-Origin: *');

class InstansiController extends Controller
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
        $query = Instansi::find();
        $query->andFilterWhere(['id' => @$_GET['id']]);
        $query->andFilterWhere(['id_induk' => @$_GET['id_induk']]);
        $query->andFilterWhere(['id_instansi_jenis' => @$_GET['id_instansi_jenis']]);
        $query->andFilterWhere(['id_instansi_lokasi' => @$_GET['id_instansi_lokasi']]);
        $query->andFilterWhere(['like','nama', @$_GET['nama']]);

        return $query->all();
    }

    public function actionView($id)
    {
        $model = Instansi::findOne($id);
        
        if($model === null) {
            Yii::$app->response->statusCode = 403;
            return [
                'message' => 'Instansi tidak ditemukan',
            ];
        }
        
        return $model;
    }

}


