<?php

namespace app\commands;

use app\models\InstansiPegawai;
use app\models\RekapJenis;
use app\models\RekapPegawaiBulan;
use app\models\Schedule;
use yii\console\Controller;
use yii\helpers\Console;

class RekapPegawaiBulanController extends Controller
{
    public $bulan;

    public function options($actionID)
    {
        return ['bulan'];
    }

    public function optionAliases()
    {
        return [
            'bulan' => 'bulan',
        ];
    }

    public function actionUpdateRekapPembayaran($bulan=null, $id_instansi=null)
    {
        ini_set('memory_limit', -1);

        $model = new Schedule([
            'nama' => '/rekap-pegawai-bulan/update-rekap-pembayaran',
            'waktu_buat' => date('Y-m-d H:i:s'),
        ]);
        $model->save();

        $tahun = date('Y');

        if ($bulan == null) {
            $bulan = date('n');
        }

        if ($this->bulan == 'mundur' AND in_array(date('n'), [1,2,3,4,5])) {
            $datetime = \DateTime::createFromFormat('Y-m', $tahun . '-' . $bulan);
            $datetime->modify('-1 month');

            $tahun = $datetime->format('Y');
            $bulan = $datetime->format('n');
        }

        $allInstansipegawai = InstansiPegawai::find()
            ->andFilterWhere(['id_instansi' => $id_instansi])
            ->filterByBulanTahun($bulan, $tahun)
            ->all();

        $done = 0;
        $total = count($allInstansipegawai);

        Console::startProgress($done, $total);
        foreach ($allInstansipegawai as $instansiPegawai) {

            if ($instansiPegawai->pegawai === null OR $instansiPegawai->instansi === null) {
                continue;
            }

            if ($instansiPegawai->pegawai->status_hapus != null) {
                continue;
            }

            //$instansiPegawai->pegawai->getPotonganBulan($bulan, $tahun);

            RekapPegawaiBulan::updateOrCreate([
                'tahun' => $tahun,
                'bulan' => $bulan,
                'id_pegawai' => $instansiPegawai->id_pegawai,
                'id_rekap_jenis' => RekapJenis::JUMLAH_TPP_KOTOR,
                'nilai' => $instansiPegawai->pegawai->getRupiahTPPSebelumPajak($bulan, [
                    'tahun' => $tahun,
                ]),
            ]);

            Console::updateProgress($done++, $total);
        }

        Console::endProgress();
    }
}
