<?php


namespace app\modules\api\controllers;

use app\models\InstansiPegawai;
use app\models\PegawaiGolongan;
use yii\web\Controller;

class PegawaiGolonganController extends Controller
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
        $query = PegawaiGolongan::find();

        $query->joinWith([
            'pegawai'
        ]);
        $query->andWhere(['pegawai.nip'=>$nip]);

        $list = [];

        /* @var $data \app\models\PegawaiGolongan */
        foreach($query->all() as $data) {
            $list[] = [
                'id' => $data->id,
                'nip_pegawai' => @$data->pegawai->nip,
                'nama_pegawai' => @$data->pegawai->nama,
                'nama_golongan' => @$data->golongan->golongan,
                'tanggal_berlaku' => $data->tanggal_berlaku,
                'tanggal_mulai' => $data->tanggal_mulai,
                'tanggal_selesai' => $data->tanggal_selesai,
            ];
        }

        return $list;

        return $query->all();
    }
}

