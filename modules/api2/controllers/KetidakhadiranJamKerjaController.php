<?php


namespace app\modules\api2\controllers;


use app\modules\api2\models\KetidakhadiranJamKerja;
use app\models\InstansiPegawai;
use app\modules\api2\models\Pegawai;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\modules\kinerja\models\KegiatanStatus;
use app\components\Session;

class KetidakhadiranJamKerjaController extends Controller
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

    public function actionIndex($nip, $tahun=null)
    {
        $pegawai = $this->findPegawaiByNip($nip);

        $data = KetidakhadiranJamKerja::restApiIndex([
            'id_pegawai' => @$pegawai->id,
            'tahun' => @$tahun ?? date('Y'),
        ]);

        return $data;
    }

    public function actionIndexBawahan($nip, $namaPegawai=null, $tahun=null)
    {
        $pegawai = $this->findPegawaiByNip($nip);

        $data = KetidakhadiranJamKerja::restApiIndexBawahan([
            'tahun' => @$tahun ?? date('Y'),
            'namaPegawai' => @$namaPegawai,
            'id_jabatan' => @$pegawai->instansiPegawai->jabatan->id,
        ]);

        return $data;
    }

    public function actionSetuju($id)
    {
        $model = $this->findKetidakhadiranJamKerja($id);

        if($model===null) {
            return [
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ];
        }

        $model->id_ketidakhadiran_jam_kerja_status = 1;

        if($model->save()) {
            return [
                'success' => true,
                'message' => 'Data berhasil disetujui'
            ];
        }
    }

    public function actionTolak($id)
    {
        $model = $this->findKetidakhadiranJamKerja($id);

        if($model===null) {
            return [
                'success' => false,
                'success' => 'Data tidak ditemukan'
            ];
        }

        $model->id_ketidakhadiran_jam_kerja_status = 3;

        if($model->save()){
            return [
                'success' => true,
                'message' => 'Data berhasil ditolak'
            ];
        }
    }

    protected function findPegawaiByNip($nip)
    {
        $model = Pegawai::findOne(['nip' => $nip]);
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

        protected function findKetidakhadiranJamKerja($id)
    {
        if (($model = KetidakhadiranJamKerja::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
