<?php


namespace app\modules\api2\controllers;


use app\modules\api2\models\KegiatanTahunan;
use app\models\InstansiPegawai;
use app\modules\api2\models\Pegawai;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\modules\kinerja\models\KegiatanStatus;
use app\components\Session;
use DateTime;
use Yii;

class KegiatanTahunanController extends Controller
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
        $tahun = Yii::$app->request->get('tahun');
        $id_kegiatan_status = Yii::$app->request->get('id_kegiatan_status');
        $id_kegiatan_tahunan_versi = Yii::$app->request->get('id_kegiatan_tahunan_versi');
        $id_kegiatan_tahunan_jenis = Yii::$app->request->get('id_kegiatan_tahunan_jenis');

        if ($id_kegiatan_status == 0 OR $id_kegiatan_status == null){
            $id_kegiatan_status = '';
        }

        if ($tahun == null) {
            $tahun = date('Y');
        }

        $pegawai = $this->findPegawaiByNip($nip);

        $data = KegiatanTahunan::restApiIndex([
            'id_pegawai' => $pegawai->id,
            'tahun' => $tahun,
            'id_kegiatan_status' => $id_kegiatan_status,
            'id_kegiatan_tahunan_jenis' => $id_kegiatan_tahunan_jenis,
            'id_kegiatan_tahunan_versi' => $id_kegiatan_tahunan_versi,
        ]);

        return $data;
    }

    public function actionIndexBulanIni($nip)
    {
        $pegawai = $this->findPegawaiByNip($nip);

        $id_kegiatan_tahunan_jenis = Yii::$app->request->get('id_kegiatan_tahunan_jenis');
        $bulan = Yii::$app->request->get('bulan');
        $tahun = Yii::$app->request->get('tahun');

        $data = KegiatanTahunan::restApiIndexBulanIni([
            'id_pegawai' => $pegawai->id,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'id_kegiatan_tahunan_jenis' => $id_kegiatan_tahunan_jenis
        ]);

        return $data;
    }

    public function actionIndexBawahan($nip, $id_pegawai_bawahan=null, $id_kegiatan_status=null)
    {
        $tahun = Yii::$app->request->get('tahun');
        $bulan = Yii::$app->request->get('bulan');
        $id_pegawai_bawahan = Yii::$app->request->get('id_pegawai_bawahan');
        $id_kegiatan_status = Yii::$app->request->get('id_kegiatan_status');
        $id_kegiatan_tahunan_jenis = Yii::$app->request->get('id_kegiatan_tahunan_jenis');

        if ($id_pegawai_bawahan == 0 OR $id_pegawai_bawahan == null) {
            $id_pegawai_bawahan = '';
        }

        if ($id_kegiatan_status == 0 OR $id_kegiatan_status == null){
            $id_kegiatan_status = '';
        }

        if ($tahun == null) {
            $tahun = date('Y');
        }

        $pegawai = $this->findPegawaiByNip($nip);

        $data = KegiatanTahunan::restApiIndexBawahan([
            'bulan' => $bulan,
            'tahun' => $tahun,
            'id_pegawai' => $id_pegawai_bawahan,
            'id_jabatan' => $pegawai->instansiPegawai->jabatan->id,
            'id_kegiatan_status' => $id_kegiatan_status,
            'id_kegiatan_tahunan_jenis' => $id_kegiatan_tahunan_jenis
        ]);

        return $data;
    }

    public function actionList($nip, $id_kegiatan_status=null)
    {
        $pegawai = $this->findPegawaiByNip($nip);

        $tahun = $tahun ?? date('Y');
        $bulan = date('m');
        $hari = date('d');

        $tanggal = $tahun.'-'.$bulan.'-'.$hari;

        $instansiPegawai = InstansiPegawai::find()
                    ->andWhere(['id_pegawai' => $pegawai->id])
                    ->andWhere('tanggal_mulai <= :tanggal and tanggal_selesai >= :tanggal', [':tanggal' => $tanggal])
                    ->andWhere(['status_hapus' => 0])
                    ->one();

        $list = KegiatanTahunan::getList([
                'id_pegawai' => @$instansiPegawai->id_pegawai,
                'id_instansi_pegawai' => $instansiPegawai->id,
                'id_kegiatan_status' => @$id_kegiatan_status,
                'tahun' => $tahun,
                'hirarki' => true
            ]);

        $data = [];

        foreach ($list as $key => $value) {
            $model = KegiatanTahunan::findOne($key);
            $satuan = $model->satuan_kuantitas;
            array_push($data, ['id' => $key, 'nama' => $value, 'satuan' => $satuan]);
        }

        return [
            'status' => 'success',
            'data' => $data
        ];
    }

    public function actionView($id, $bulan=null)
    {
        $model = $this->findKegiatanTahunan($id);

        return $model->restJson([
            'bulan' => $bulan,
        ]);
    }

    public function actionCreate()
    {
        return false;
        $requestPost = \Yii::$app->request->post();

        if ($requestPost) {
            if (@$requestPost['nip']) {
                $pegawai = $this->findPegawaiByNip($requestPost['nip']);

                $model = new KegiatanTahunan();
                $model->attributes = $requestPost;
                $model->id_pegawai = $pegawai->id;
                $model->id_kegiatan_tahunan_versi = 2;
                $model->tahun = date('Y');

                if ($model->save()) {
                    $model->generateKegiatanBulanan();

                    return [
                        'status' => 'success',
                        'message' => 'Data Berhasil Disimpan',
                        'data' => $model
                    ];
                } else {
                    return [
                        'status' => 'failed',
                        'message' => 'Data Gagal Disimpan',
                        'messageError' => $model->errors
                    ];
                }
            } else {
                return [
                    'status' => 'failed',
                    'message' => 'Data Gagal Disimpan Error : NIP Tidak Ditemukan',
                ];
            }
        }

        return [
            'status' => 'failed',
        ];
    }

    public function actionUpdate($id)
    {
        $requestPost = \Yii::$app->request->post();

        if ($requestPost) {

            $model = $this->findKegiatanTahunan($id);
            $model->attributes = $requestPost;

            $model->setNomorSkp();
            $model->setIdInstansiPegawai();

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

    public function actionUpdateKegiatanAtasan($id)
    {
        $requestPost = \Yii::$app->request->post();

        if ($requestPost) {

            $model = $this->findKegiatanTahunan($id);
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
        $model = $this->findKegiatanTahunan($id);
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

    public function actionPeriksa($id)
    {
        $model = $this->findKegiatanTahunan($id);

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
        $model = $this->findKegiatanTahunan($id);

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
        $model = $this->findKegiatanTahunan($id);

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

    public function actionListAtasan($nip, $id_instansi_pegawai=null)
    {
        $pegawai = $this->findPegawaiByNip($nip);

        $data = KegiatanTahunan::restApiListAtasan([
            'id_pegawai' => $pegawai->id,
            'id_instansi_pegawai' => $id_instansi_pegawai,
        ]);

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

    protected function findKegiatanTahunan($id)
    {
        if (($model = KegiatanTahunan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
