<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 04/02/2020
 * Time: 06.48
 */

namespace app\modules\api2\controllers;

use app\modules\api2\models\KegiatanKetidakhadiran;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class KegiatanKetidakhadiranController extends Controller
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

    public function actionIndex($nip)
    {
        $params['nip'] = $nip;

        $data = KegiatanKetidakhadiran::restApiIndex($params);

        return [
            'message' => 'Data berhasil diambil',
            'data' => $data,
        ];
    }

    public function actionView($id)
    {
        $model = $this->findKegiatanHarian($id);

        return $model->restJson();
    }

    public function actionCreate()
    {
        $requestPost = \Yii::$app->request->post();

        if ($requestPost) {
            
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model===null) {
            return [
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ];
        }

        if ($model->delete()) {
            return [
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ];
        }
    }

    protected function findModel($id)
    {
        if(($model = KegiatanKetidakhadiran::findOne($id)) != null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
