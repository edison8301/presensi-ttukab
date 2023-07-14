<?php


namespace app\commands;


use app\components\Helper;
use app\models\InstansiPegawai;
use app\models\PegawaiRekapBulan;
use app\models\PegawaiRekapJenis;
use yii\console\Controller;
use yii\helpers\Console;

class PegawaiRekapBulanController extends Controller
{
    public function actionIndex()
    {
        $this->stdout('Hello World');
    }

    public function actionUpdateRupiahTppAwal($kode_keterangan=null)
    {
        $bulan = 6;
        $tahun = 2020;

        $query = InstansiPegawai::query([
            'bulan' => $bulan,
            'tahun' => $tahun
        ]);

        $total = $query->count();
        $done = 0;

        $keterangan = null;

        Console::startProgress($done,$total);
        foreach($query->all() as $data) {

            $model = \app\modules\tukin\models\Pegawai::findOne($data->id_pegawai);
            $nilai = $model->getTppAwal($bulan);
            $nilai = Helper::rp($nilai,0);

            if($kode_keterangan=='nama_jabatan') {
                $keterangan = @$data->jabatan->id.' - '.@$data->jabatan->nama;
            }

            PegawaiRekapBulan::createOrUpdate([
                'id_pegawai'=>$data->id_pegawai,
                'bulan'=> $bulan,
                'tahun' => $tahun,
                'id_pegawai_rekap_jenis' => PegawaiRekapJenis::RUPIAH_TPP_AWAL,
                'nilai' => strval($nilai),
                'keterangan' => $keterangan
            ]);

            $done++;

            Console::updateProgress($done,$total);
        }

        Console::endProgress();
    }
}
