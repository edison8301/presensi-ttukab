<?php


namespace app\modules\api2\controllers;


use app\modules\api2\models\Peta;
use app\modules\api2\models\Pegawai;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class PetaController extends Controller
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

    public function actionCreate()
    {
        $requestPost = Yii::$app->request->post();
        $pegawai = $this->findPegawaiByNip($requestPost['nip']);

        if ($pegawai == null) {
            Yii::$app->response->statusCode = 403;
            return [
                'message' => 'Pegawai tidak ditemukan',
            ];
        }

        $model = new Peta();
        $model->nama = $pegawai->nama;
        $model->id_pegawai = $pegawai->id;
        $model->id_peta_jenis = 1;
        $model->latitude = @$requestPost['latitude'];
        $model->longitude = @$requestPost['longitude'];
        $model->status_rumah = 1;
        $model->setLatlong();
        
        if ($model->save()) {
            Yii::$app->response->statusCode = 200;
            return [
                'message' => 'Data Berhasil Disimpan',
                'data' => $model
            ];
        }

        Yii::$app->response->statusCode = 403;
        return [
            'message' => 'Data Gagal Disimpan',
            'messageError' => $model->errors
        ];
    }

    public function actionUpdate()
    {
        $requestPost = Yii::$app->request->post();
        $peta = $this->updateOrCreate($requestPost);

        if ($peta == false) {
            Yii::$app->response->statusCode = 403;
            return [
                'message' => 'Data Gagal Diupdate',
            ];   
        }

        Yii::$app->response->statusCode = 200;
        return [
            'message' => 'Data Berhasil Diupdate',
        ];
    }

    public function updateOrCreate($post) {
        $pegawai = $this->findPegawaiByNip($post['nip']);
        if ($pegawai == null) {
            Yii::$app->response->statusCode = 403;
            return [
                'message' => 'Pegawai tidak ditemukan',
            ];
        }

        $model = Peta::find()
            ->andWhere(['id_pegawai' => $pegawai->id])
            ->andWhere(['status_rumah' => 1])
            ->one();

        if ($model == null) {
            $model = new Peta();
            $model->nama = $pegawai->nama;
            $model->id_pegawai = $pegawai->id;
            $model->id_peta_jenis = 1;
        }

        $model->status_kunci = 1;
        $model->status_rumah = 1;
        $model->latitude = $post['latitude'];
        $model->longitude = $post['longitude'];
        $model->setLatlong();

        if ($model->save()) {
            return true;
        }

        return false;
    }

    public function actionViewPetaPegawai($nip)
    {
        $pegawai = $this->findPegawaiByNip($nip);
        if ($pegawai == null) {
            Yii::$app->response->statusCode = 403;
            return [
                'message' => 'Pegawai tidak ditemukan',
            ];
        }

        $model = Peta::find()
            ->andWhere(['id_pegawai' => $pegawai->id])
            ->andWhere(['status_rumah' => 1])
            ->one();

        if ($model == null) {
            Yii::$app->response->statusCode = 403;
            return [
                'message' => 'Peta tidak ditemukan',
                'status' => false,
            ];
        }

        Yii::$app->response->statusCode = 200;
        return [
            'message' => 'Peta ditemukan',
            'status' => true,
            'data' => $model,
        ];
    }

    protected function findPegawaiByNip($nip)
    {
        $model = Pegawai::findOne(['nip' => $nip]);
        return $model;
    }

    protected function findPeta($id)
    {
        $model = Peta::findOne($id);
        return $model;
    }
}
