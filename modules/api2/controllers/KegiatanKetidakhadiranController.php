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

                if (Yii::$app->request->post('foto_pendukung_encode') != null) {
                    $foto_decode = base64_decode(Yii::$app->request->post('foto_pendukung_encode'));
                    $path = Yii::$app->basePath . '/web/uploads/foto-pendukung/';
                    file_put_contents($path . $model->foto_pendukung, $foto_decode);
                }

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
        $fotoPendukungLama = $model->foto_pendukung;

        if (Yii::$app->request->post()) {
            $fotoPendukung = $model->foto_pendukung;
            $model->attributes = Yii::$app->request->post();

            $statusUpload = false;
            if (Yii::$app->request->post('foto_pendukung') != null) {
                $statusUpload = true;

                $fotoPendukung = $model->foto_pendukung;
                $fotoPendukungArray = explode('.', $fotoPendukung);
                $ext = $fotoPendukungArray[count($fotoPendukungArray)-1];

                $model->foto_pendukung = time() . '_' . $model->nip . '.' . $ext;
            }

            if (Yii::$app->request->post('foto_pendukung') == null) {
                $model->foto_pendukung = $fotoPendukung;
            }

            $model->updated_at = date('Y-m-d H:i:s');

            if ($model->save()) {

                if ($statusUpload && Yii::$app->request->post('foto_pendukung_encode') != null) {
                    $foto_decode = base64_decode(Yii::$app->request->post('foto_pendukung_encode'));
                    $path = Yii::$app->basePath . '/web/uploads/foto-pendukung/';
                    file_put_contents($path . $model->foto_pendukung, $foto_decode);

                    $pathLama = Yii::$app->basePath . '/web/uploads/foto-pendukung/' . $fotoPendukungLama;
                    if ($fotoPendukungLama != null AND file_exists($pathLama) !== false) {
                        unlink($pathLama);
                    }
                }

                Yii::$app->response->statusCode = 200;
                return [
                    'status' => 'success',
                    'message' => 'Data Berhasil Disimpan',
                    'data' => $model
                ];
            }
        }

        Yii::$app->response->statusCode = 400;
        return [
            'status' => 'failed',
            'message' => 'Data Gagal Disimpan',
            'messageError' => $model->errors
        ];
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $fotoPendukung = $model->foto_pendukung;

        if ($model===null) {
            return [
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ];
        }

        if ($model->delete()) {

            $path = Yii::$app->basePath . '/web/uploads/foto-pendukung/' . $fotoPendukung;
            if ($fotoPendukung != null AND file_exists($path) !== false) {
                unlink($path);
            }

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
