<?php


namespace app\modules\api\controllers;

use app\models\PegawaiSertifikasi;
use Yii;
use yii\web\Controller;

class PegawaiSertifikasiController extends Controller
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

    public function actionIndex()
    {
        $id_pegawai = Yii::$app->request->get('id_pegawai');
        $id_instansi = Yii::$app->request->get('id_instansi');
        $id_pegawai_sertifikasi_jenis = Yii::$app->request->get('id_pegawai_sertifikasi_jenis');

        $query = PegawaiSertifikasi::find();
        $query->andFilterWhere(['id_pegawai_sertifikasi_jenis' => $id_pegawai_sertifikasi_jenis]);

        if ($id_instansi != null) {
            $query->andWhere(['id_pegawai' => $id_pegawai]);
        }

        if ($id_instansi != null) {
            $query->andWhere(['id_instansi' => $id_instansi]);
        }

        $allPegawaiSertifikasi = $query->all();

        return $allPegawaiSertifikasi;
    }

    public function actionIndexGuru()
    {
        $id_pegawai = Yii::$app->request->get('id_pegawai');
        $id_instansi = Yii::$app->request->get('id_instansi');

        $query = PegawaiSertifikasi::find();
        $query->andFilterWhere(['id_pegawai_sertifikasi_jenis' => 1]);

        if ($id_instansi != null) {
            $query->andWhere(['id_pegawai' => $id_pegawai]);
        }

        if ($id_instansi != null) {
            $query->andWhere(['id_instansi' => $id_instansi]);
        }

        $allPegawaiSertifikasi = $query->all();

        return $allPegawaiSertifikasi;
    }
}
