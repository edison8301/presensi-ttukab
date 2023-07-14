<?php

namespace app\commands;

use app\components\Helper;
use app\models\InstansiPegawai;
use app\models\Pegawai;
use app\models\RekapInstansiBulan;
use app\models\RekapJenis;
use app\models\Schedule;
use Yii;
use app\jobs\RefreshRekapJob;
use app\models\Instansi;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;

class RekapInstansiBulanController extends Controller
{
    public function actionUpdatePersenPotonganCkhpRataRata($bulan=null)
    {
        $tahun = date('Y');

        if ($bulan == null) {
            $bulan = date('n');
        }

        $datetime = \DateTime::createFromFormat('Y-n-d', date('Y') . '-' . $bulan . '-01');
        $tanggal = $datetime->format('Y-m-15');

        $listInstansi = Instansi::find()->all();

        $done = 0;
        $total = count($listInstansi);

        Console::startProgress($done, $total);
        foreach($listInstansi as $instansi)
        {
            $listInstansiPegawai = $instansi->getListInstansiPegawaiBerlaku([
                'tanggal' => $tanggal,
            ]);

            $nilai = 0;

            /* @var $instansiPegawai InstansiPegawai */
            foreach($listInstansiPegawai as $instansiPegawai)
            {
                if($instansiPegawai->pegawai === null) {
                    continue;
                }

                $potongan = $instansiPegawai->pegawai->getTotalPotonganCkhp([
                    'tahun' => date('Y'),
                    'bulan' => $bulan,
                ]);

                if ($potongan != 0) {
                    $nilai++;
                }
            }

            $rekapInstansiBulan = RekapInstansiBulan::findOrCreate([
                'id_instansi' => $instansi->id,
                'tahun' => $tahun,
                'bulan' => $bulan,
                'id_rekap_jenis' => RekapJenis::JUMLAH_PEGAWAI_POTONGAN_CKHP,
            ]);

            $rekapInstansiBulan->nilai = $nilai;
            $rekapInstansiBulan->save();

            Console::updateProgress($done++, $total);
        }

        Console::endProgress();
    }

    public function actionUpdateJumlahPegawaiPotonganCkhp($bulan=null)
    {
        $model = new Schedule([
            'nama' => '/rekap-instansi-bulan/update-jumlah-pegawai-potongan-ckhp',
            'waktu_buat' => date('Y-m-d H:i:s'),
        ]);
        $model->save();

        $tahun = date('Y');

        if ($bulan == null) {
            $bulan = date('n');
        }

        $datetime = \DateTime::createFromFormat('Y-n-d', date('Y') . '-' . $bulan . '-01');
        $tanggal = $datetime->format('Y-m-15');

        $listInstansi = Instansi::find()->all();

        $done = 0;
        $total = count($listInstansi);

        Console::startProgress($done, $total);
        foreach($listInstansi as $instansi)
        {
            $listInstansiPegawai = $instansi->getListInstansiPegawaiBerlaku([
                'tanggal' => $tanggal,
            ]);

            $nilai = 0;

            /* @var $instansiPegawai InstansiPegawai */
            foreach($listInstansiPegawai as $instansiPegawai)
            {
                if($instansiPegawai->pegawai === null) {
                    continue;
                }

                $potongan = $instansiPegawai->pegawai->getTotalPotonganCkhp([
                    'tahun' => date('Y'),
                    'bulan' => $bulan,
                ]);

                if ($potongan != 0) {
                    $nilai++;
                }
            }

            $rekapInstansiBulan = RekapInstansiBulan::findOrCreate([
                'id_instansi' => $instansi->id,
                'tahun' => $tahun,
                'bulan' => $bulan,
                'id_rekap_jenis' => RekapJenis::JUMLAH_PEGAWAI_POTONGAN_CKHP,
            ]);

            $rekapInstansiBulan->nilai = $nilai;
            $rekapInstansiBulan->save();

            Console::updateProgress($done++, $total);
        }

        Console::endProgress();
    }

    public function actionUpdateJumlahPegawaiPotonganIki($bulan=null)
    {
        $model = new Schedule([
            'nama' => '/rekap-instansi-bulan/update-jumlah-pegawai-potongan-iki',
            'waktu_buat' => date('Y-m-d H:i:s'),
        ]);
        $model->save();

        $tahun = date('Y');

        if ($bulan == null) {
            $bulan = date('n');
        }

        $datetime = \DateTime::createFromFormat('Y-n-d', date('Y') . '-' . $bulan . '-01');
        $tanggal = $datetime->format('Y-m-15');

        $listInstansi = Instansi::find()->all();

        $done = 0;
        $total = count($listInstansi);

        Console::startProgress($done, $total);
        foreach($listInstansi as $instansi)
        {
            $listInstansiPegawai = $instansi->getListInstansiPegawaiBerlaku([
                'tanggal' => $tanggal,
            ]);

            $nilai = 0;

            /* @var $instansiPegawai InstansiPegawai */
            foreach($listInstansiPegawai as $instansiPegawai)
            {
                if($instansiPegawai->pegawai === null) {
                    continue;
                }

                $potongan = $instansiPegawai->pegawai->getPersenPotonganSkpBulanan($bulan, $tahun, [
                    'potonganCkhp' => false,
                ]);

                if ($potongan != 0) {
                    $nilai++;
                }
            }

            $rekapInstansiBulan = RekapInstansiBulan::findOrCreate([
                'id_instansi' => $instansi->id,
                'tahun' => $tahun,
                'bulan' => $bulan,
                'id_rekap_jenis' => RekapJenis::JUMLAH_PEGAWAI_POTONGAN_IKI,
            ]);

            $rekapInstansiBulan->nilai = $nilai;
            $rekapInstansiBulan->save();

            Console::updateProgress($done++, $total);
        }

        Console::endProgress();
    }
}
