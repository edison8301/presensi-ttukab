<?php


namespace app\modules\apidaspeg\controllers;

use app\models\InstansiPegawai;
use yii\web\Controller;

class PegawaiInstansiController extends Controller
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

    public function actionIndex($nip)
    {
        $query = InstansiPegawai::find();

        $query->joinWith([
            'pegawai'
        ]);
        $query->andWhere(['pegawai.nip'=>$nip]);

        $list = [];

        foreach($query->all() as $instansiPegawai) {
            $list[] = [
                'id' => $instansiPegawai->id,
                'nip_pegawai' => @$instansiPegawai->pegawai->nip,
                'nama_pegawai' => @$instansiPegawai->pegawai->nama,
                'nama_jabatan' => @$instansiPegawai->jabatan->nama,
                'nama_instansi' => @$instansiPegawai->instansi->nama,
                'tanggal_berlaku' => $instansiPegawai->tanggal_berlaku,
                'tanggal_mulai' => $instansiPegawai->tanggal_mulai,
                'tanggal_selesai' => $instansiPegawai->tanggal_selesai,
                'jenis_jabatan' => $instansiPegawai->jenisJabatan
            ];
        }

        return $list;
    }
}

