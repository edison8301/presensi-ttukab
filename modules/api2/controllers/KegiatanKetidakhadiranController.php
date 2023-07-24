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

            $model = new KegiatanKetidakhadiran();
            $model->attributes = Yii::$app->request->post();

            if ($model->foto_pendukung != null) {
                $fotoPendukung = $model->foto_pendukung;
                $fotoPendukungArray = explode('.', $fotoPendukung);
                $ext = $fotoPendukungArray[count($fotoPendukungArray)-1];

                $model->foto_pendukung = time() . '_' . $model->nip . '.' . $ext;
            }

            $model->created_at = date('Y-m-d H:i:s');
            $model->updated_at = date('Y-m-d H:i:s');

            if ($model->save()) {

                $foto_decode = base64_decode(Yii::$app->request->post('foto_pendukung_encode'));
                $path = Yii::$app->basePath . '/web/uploads/foto-pendukung/';
                file_put_contents($path . $model->foto_pendukung, $foto_decode);

                Yii::$app->response->statusCode = 200;
                return [
                    'status' => 'success',
                    'message' => 'Data Berhasil Disimpan',
                    'data' => $model
                ];
            } else {
                Yii::$app->response->statusCode = 400;
                return [
                    'status' => 'failed',
                    'message' => 'Data Gagal Disimpan',
                    'messageError' => $model->errors
                ];
            }
        }

        Yii::$app->response->statusCode = 400;
        return [
            'statue' => 'failed',
        ];
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
