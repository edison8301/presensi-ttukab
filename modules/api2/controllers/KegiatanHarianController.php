<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 04/02/2020
 * Time: 06.48
 */

namespace app\modules\api2\controllers;

use app\modules\api2\models\KegiatanHarian;
use app\modules\api2\models\KegiatanTahunan;
use app\modules\api2\models\Pegawai;
use app\modules\kinerja\models\KegiatanStatus;
use Yii;
use app\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class KegiatanHarianController extends Controller
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

    public function actionIndex($nip, $tanggal=null, $bulan=null, $tahun=null, $id_kegiatan_status=null, $limit=null)
    {
        $pegawai = $this->findPegawaiByNip($nip);

        if ($pegawai == null) {
            return [];
        }

        if($id_kegiatan_status == 0 OR $id_kegiatan_status == null){
            $id_kegiatan_status = '';
        }

        $data = KegiatanHarian::restApiIndex([
            'id_pegawai' => $pegawai->id,
            'tanggal' => $tanggal,
            'bulan' => $bulan,
            'tahun' => $tahun ?? date('Y'),
            'id_kegiatan_status' => $id_kegiatan_status,
            'limit' => $limit,
        ]);

        return $data;
    }

    public function actionIndexBawahan($nip, $tanggal=null, $bulan=null, $tahun=null, $id_pegawai_bawahan=null, $id_kegiatan_status=null)
    {
        $pegawai = $this->findPegawaiByNip($nip);

        if($id_pegawai_bawahan == 0 OR $id_pegawai_bawahan == null) {
            $id_pegawai_bawahan = '';
        }

        if($id_kegiatan_status == 0 OR $id_kegiatan_status == null){
            $id_kegiatan_status = '';
        }

        $data = KegiatanHarian::restApiIndexBawahan([
            'tanggal' => @$tanggal,
            'bulan' => @$bulan,
            'tahun' => @$tahun ?? date('Y'),
            'id_pegawai' => @$id_pegawai_bawahan,
            'id_jabatan' => @$pegawai->instansiPegawai->jabatan->id,
            'id_kegiatan_status' => @$id_kegiatan_status
        ]);

        return $data;
    }

    public function actionView($id)
    {
        $model = $this->findKegiatanHarian($id);

        return $model->restJson();
    }

    protected function findPegawaiByNip($nip)
    {
        $model = Pegawai::findOne(['nip' => $nip]);
        if ($model !== null) {
            return $model;
        } else {
            return null;
            // throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionCreate()
    {
        return false;
        $requestPost = \Yii::$app->request->post();

        if ($requestPost) {
            $pegawai = $this->findPegawaiByNip($requestPost['nip']);
            $model = new KegiatanHarian();
            $model->attributes = Yii::$app->request->post();
            $model->id_pegawai = $pegawai->id;
            $model->id_instansi_pegawai = $pegawai->getInstansiPegawaiBerlaku()->id;
            $model->id_kegiatan_harian_versi = 2;
            $model->setWaktuDibuat();
            $model->setScenarioKegiatan();

            $model->tanggal = date('Y-m-d');

            if ($model->save()) {
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
        $model = KegiatanHarian::findOne($id);
        $tanggal_lama = $model->tanggal;

        $model->attributes = Yii::$app->request->post();
        $model->tanggal = $tanggal_lama;

        if($model->id_kegiatan_tahunan === 'null'){
            $model->id_kegiatan_tahunan = null;
        }

        if($model->id_kegiatan_harian_tambahan === 'null'){
            $model->id_kegiatan_harian_tambahan = null;
        }

        if($model->save()) {
            return [
                'status' => 'success',
                'message' => 'Data Berhasil Diperbarui',
                'data' => $model
            ];
        } else {
            return [
                'status' => 'failed',
                'message' => 'Data Gagal Disimpan',
                'messageError' => $model->errors
            ];
        }

        return [
            'statue' => 'failed',
        ];
    }

    public function actionDelete($id)
    {
        $model = KegiatanHarian::findOne($id);

        if($model===null) {
            return [
                'success'=>false,
                'message'=>'Data tidak ditemukan'
            ];
        }

        if($model->delete()) {
            return [
                'success'=>true,
                'message'=>'Data berhasil dihapus'
            ];
        }
    }

    public function actionPeriksa($id)
    {
        $model = KegiatanHarian::findOne($id);

        if($model===null) {
            return [
                'success'=> false,
                'message'=>'Data tidak ditemukan'
            ];
        }

        $model->setIdKegiatanStatus(KegiatanStatus::PERIKSA);

        if($model->save()) {
            return [
                'success'=> true,
                'message'=>'Kegiatan dikirim untuk diperiksa'
            ];
        }
    }

    public function actionSetuju($id)
    {
        $model = KegiatanHarian::findOne($id);

        if($model===null) {
            return [
                'success' => false,
                'message' => 'Kegiatan tidak ditemukan'
            ];
        }

        $model->setIdKegiatanStatus(KegiatanStatus::SETUJU);

        if($model->save()) {
            return [
                'success' => true,
                'message' => 'Kegiatan berhasil disetujui'
            ];
        }
    }

    public function actionTolak($id)
    {
        $model = KegiatanHarian::findOne($id);

        if($model===null) {
            return [
                'success' => false,
                'success' => 'Kegiatan tidak ditemukan'
            ];
        }

        $model->setIdKegiatanStatus(KegiatanStatus::TOLAK);

        if($model->save()){
            return [
                'success' => true,
                'message' => 'Kegiatan berhasil ditolak'
            ];
        }
    }

    protected function findKegiatanHarian($id)
    {
        if(($model = KegiatanHarian::findOne($id)) != null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
