<?php


namespace app\modules\api2\controllers;


use app\modules\api2\models\KegiatanBulanan;
use app\modules\api2\models\Pegawai;
use app\modules\api2\models\KegiatanHarian;
use app\modules\kinerja\models\KegiatanTahunan;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\modules\kinerja\models\KegiatanStatus;
use app\components\Session;
use app\components\Helper;

class KegiatanBulananController extends Controller
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

    public function actionIndex($nip, $mode=null, $bulan=null, $tahun=null)
    {
        $pegawai = $this->findPegawaiByNip($nip);

        $data = KegiatanBulanan::restApiIndex([
            'bulan' => $bulan,
            'tahun' => $tahun ?? date('Y'),
            'id_pegawai' => $pegawai->id,
            'id_jabatan' => @$pegawai->instansiPegawai->jabatan->id,
            'mode' => $mode
        ]);
        return $data;
    }

    public function actionIndexByKegiatanTahunan($id_kegiatan_tahunan)
    {
        $query = KegiatanBulanan::find();
        $query->andWhere(['id_kegiatan_tahunan' => $id_kegiatan_tahunan]);

        $output = [];

        foreach ($query->all() as $kegiatanBulanan) {
            $output[] = $kegiatanBulanan->restJson();
        }

        return $output;
    }

    public function actionView($id)
    {
        $model = $this->findKegiatanBulanan($id);

        return $model->restJson();
    }

    public function actionIndexKegiatanHarian($id, $tahun=null)
    {
        $model = $this->findKegiatanBulanan($id);

        $data = KegiatanHarian::restApiIndexByKegiatanTahunan([
            'id_kegiatan_tahunan' => $model->id_kegiatan_tahunan,
            'tahun' => $tahun ?? date('Y'),
            'bulan' => $model->bulan
        ]);

        return $data;
    }

    public function actionUpdate($id)
    {
        $requestPost = \Yii::$app->request->post();

        if ($requestPost) {
            $model = $this->findKegiatanBulanan($id);
            $model->attributes = $requestPost;
            if ($model->save()) {
                return [
                    'status' => 'success',
                    'message' => 'Data Berhasil Disunting',
                    'data' => $model
                ];
            } else {
                return [
                    'status' => 'failed',
                    'message' => 'Data Gagal Disimpan Error : ',
                    'messageError' => $model->errors
                ];
            }
        }

        return [
            'status' => 'failed',
        ];
    }

    public function actionDelete($id)
    {
        $model = $this->findKegiatanBulanan($id);
        if($model->delete()) {
            return [
                'status'=>'success',
                'message' => 'Data berhasil dihapus'
            ];
        } else {
            return [
                'status'=>'fail',
                'message' => 'Data gagal dihapus dihapus'
            ];
        }
    }

    public function actionPersenRealisasiBulan($nip, $mode=null, $bulan=null)
    {
        $pegawai = $this->findPegawaiByNip($nip);

        $data = KegiatanBulanan::restApiIndex([
            'bulan' => $bulan,
            'tahun' => date('Y'),
            'id_pegawai' => $pegawai->id,
            'id_jabatan' => @$pegawai->instansiPegawai->jabatan->id,
            'mode' => $mode
        ]);

        $total_persen_realisasi = 0;

        foreach ($data as $value) {
            $total_persen_realisasi += $value['persen_realisasi'];
        }

        if(count($data) == 0){
            return [
                'total_realisasi' => (double)0,
                'total_persen_realisasi' => (double)0
            ];
        }

        $hasil = $total_persen_realisasi/count($data);

        $persen = $hasil/100;

        if($persen > 1) {
            $persen = 1;
        }

        return [
            'total_realisasi' => (double)substr($hasil, 0,4),
            'total_persen_realisasi' => (double)substr($persen, 0,4),
        ];
    }

    public function actionListBulan()
    {
        $data = [];
        for ($i=1; $i <=12 ; $i++) {
            array_push($data, [
                'nama' => Helper::getBulanLengkap($i),
                'value' => $i,
            ]);
        }
        return $data;
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

    protected function findKegiatanBulanan($id)
    {
        if (($model = KegiatanBulanan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
