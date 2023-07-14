<?php

namespace app\modules\api\controllers;

use app\components\Helper;
use app\models\InstansiPegawai;
use app\models\Jabatan;
use app\models\Pegawai;
use Yii;
use yii\web\Controller;

header('Access-Control-Allow-Origin: *');

class PegawaiController extends Controller
{
    public $modelClass = Pegawai::class;

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
        $query = Pegawai::find();
        $query->joinWith(['instansiPegawai']);
        $query->andWhere('instansi_pegawai.tanggal_mulai <= :tanggal AND instansi_pegawai.tanggal_selesai >= :tanggal',[
            ':tanggal' => date('Y-m-d')
        ]);
        $query->andFilterWhere(['instansi_pegawai.status_plt' => 0]);
        $query->andFilterWhere(['instansi_pegawai.id_instansi' => @$_GET['id_instansi']]);
        $query->andFilterWhere(['instansi_pegawai.id_jabatan' => @$_GET['id_jabatan']]);
        $query->andFilterWhere(['like','pegawai.nama', @$_GET['nama']]);

        if($limit !== null) {
            $query->limit = $limit;
        }

        $query->select([
            'pegawai.id', 'nama', 'nip', 'instansi_pegawai.id_instansi',
            'instansi_pegawai.id_jabatan', 'pegawai.tanggal_lahir'
        ]);

        return $query->all();
    }

    public function actionView()
    {
        $query = Pegawai::find();
        $query->andFilterWhere(['id' => @$_GET['id']]);
        $query->andFilterWhere(['nip' => @$_GET['nip']]);

        $model = $query->one();

        if($model === null) {
            Yii::$app->response->statusCode = 403;
            return [
                'status' => 400,
                'message' => 'Pegawai tidak ditemukan',
            ];
        }

        return [
            'status' => 200,
            'id' => $model->id,
            'nama' => $model->nama,
            'nip' => $model->nip,
            'id_instansi' => @$model->instansiPegawaiBerlaku->id_instansi,
            'id_jabatan' => @$model->instansiPegawaiBerlaku->id_jabatan,
            'tanggal_lahir' => $model->tanggal_lahir,
            'golongan' => @$model->pegawaiGolonganBerlaku->golongan->golongan,
            'jabatan' => @$model->instansiPegawaiBerlaku->jabatan->nama,
            'nama_instansi' => @$model->instansiPegawaiBerlaku->instansi->nama
        ];
    }

    public function actionInstansiPegawai($nip)
    {
        $model = $this->findModel($nip);
        return $model->findAllInstansiPegawai();
    }

    public function actionInstansiPegawaiData($id_instansi_pegawai, $relasi, $atribut)
    {
        $model = InstansiPegawai::findOne($id_instansi_pegawai);
        return @$model->$relasi->$atribut;
    }

    public function actionHelperTanggal($tanggal)
    {
        return Helper::getTanggal($tanggal);
    }

    public function findModel($nip)
    {
        $model = Pegawai::findOne(['nip' => $nip]);
        return $model;
    }

    public function actionAtasanByNip($nip)
    {
        $instansiPegawai = InstansiPegawai::find()
            ->joinWith('pegawai')
            ->andWhere(['pegawai.nip' => $nip])
            ->orderBy(['tanggal_berlaku' => SORT_DESC])
            ->one();

        return @$instansiPegawai->atasan->nip;
    }

    public function actionGolonganByNip($nip)
    {
        $model = Pegawai::findOne(['nip' => $nip]);

        return $model->golongan;
    }

    public function actionGetInstansi($nip): int
    {
        $model = Pegawai::findOne(['nip' => $nip]);

        return $model->id_instansi;
    }

}


