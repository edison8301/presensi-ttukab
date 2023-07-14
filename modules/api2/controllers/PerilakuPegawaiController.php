<?php

namespace app\modules\api2\controllers;

use app\modules\api2\models\Pegawai;
use app\modules\api2\models\PerilakuPegawai;
use yii\web\Controller;
use app\models\InstansiPegawai;
use Yii;

/**
 * Default controller for the `api2` module
 */
class PerilakuPegawaiController extends Controller
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

    /**
     * Renders the index view for the module
     * @return string
     */

    public function actionView($id_pegawai, $bulan=null, $tahun=null)
    {
        $model = Pegawai::findOne($id_pegawai);

        $bulan = $bulan == 'null' || $bulan == '' ? date('m') : $bulan;
        $tahun = $tahun ?? date('Y');

        $this->findOrCreatePerilakuPegawai($model->id, $bulan, $tahun);

        $perilakuPegawai = PerilakuPegawai::findOne([
            'id_pegawai' => $model->id,
            'bulan' => $bulan,
            'tahun' => $tahun,
        ]);

        return $perilakuPegawai->restJson();
    }

    public function actionViewNilai($id_pegawai, $id_perilaku_jenis, $bulan, $tahun)
    {
        $model = PerilakuPegawai::findOne([
            'id_pegawai' => $id_pegawai,
            'id_perilaku_jenis' => $id_perilaku_jenis,
            'bulan' => $bulan,
            'tahun' => $tahun,
        ]);

        return [
            'nilai' => $model->nilai,
        ];
    }

    public function actionUpdateNilai()
    {
        $id_pegawai =  \Yii::$app->request->post('id_pegawai');
        $id_perilaku_jenis = \Yii::$app->request->post('id_perilaku_jenis');
        $tahun = \Yii::$app->request->post('tahun');
        $bulan = \Yii::$app->request->post('bulan');
        $nilai = \Yii::$app->request->post('nilai');

        $model = PerilakuPegawai::findOne([
            'id_pegawai' => $id_pegawai,
            'id_perilaku_jenis' => $id_perilaku_jenis,
            'bulan' => $bulan,
            'tahun' => $tahun,
        ]);

        if($model == null) {
            return [
                'status' => 'fail',
                'message' => 'Data tidak ditemukan'
            ];
        }

        $model->nilai = $nilai;

        if($model->save()){
            return [
                'status' => 'success',
                'message' => 'Data berhasil disunting',
            ];
        }else{
            return [
                'status' => 'fail',
                'message' => 'Data gagal disimpan Error : ',
                'messageError' => $model->errors
            ];
        }
    }

    public function findOrCreatePerilakuPegawai($id_pegawai, $bulan, $tahun)
    {
        $perilakuPegawai = PerilakuPegawai::findOne([
            'id_pegawai' => $id_pegawai,
            'tahun' => $tahun,
            'bulan' => $bulan,
        ]);

        if($perilakuPegawai === null) {
            for ($i=1; $i<=6 ; $i++) {
                $model = new PerilakuPegawai([
                    'id_pegawai' => $id_pegawai,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'id_perilaku_jenis' => $i,
                    'nilai' => 0,
                ]);

                $model->save();
            }
        }
    }
}
