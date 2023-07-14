<?php


namespace app\modules\api\controllers;

use app\models\InstansiPegawai;
use app\models\Jabatan;
use Yii;
use yii\web\Controller;

class InstansiPegawaiController extends Controller
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

    public function actionIndex($limit=300)
    {
        $query = InstansiPegawai::find();
        $query->andWhere('tanggal_mulai <= :tanggal AND tanggal_selesai >= :tanggal',[
            ':tanggal' => date('Y-m-d')
        ]);
        $query->andFilterWhere(['id' => @$_GET['id']]);
        $query->andFilterWhere(['id_jabatan' => @$_GET['id_jabatan']]);
        $query->andFilterWhere(['id_pegawai' => @$_GET['id_pegawai']]);
        $query->andFilterWhere(['id_instansi' => @$_GET['id_instansi']]);
        if($limit !== null)
        {
            $query->limit = $limit;
        }

        return [
            'data' => $query->all(),
            'status' => 200
        ];
    }

    public function actionView()
    {
        $query = InstansiPegawai::find();
        $query->andWhere('tanggal_mulai <= :tanggal AND tanggal_selesai >= :tanggal',[
            ':tanggal' => date('Y-m-d')
        ]);
        $query->andFilterWhere(['id_pegawai' => @$_GET['id_pegawai']]);
        $model = $query->one();

        if($model === null) {
            Yii::$app->response->statusCode = 403;
            return [
                'status' => 400,
                'message' => 'Jabatan tidak ditemukan',
            ];
        }

        return [
            'data' => [
                'nip' => $model->pegawai->nip,
                'id_jabatan' => $model->id_jabatan
            ],
            'status' => 200
        ];
    }
}

