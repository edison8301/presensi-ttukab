<?php

namespace app\commands;


use Yii;
use yii\helpers\Console;
use yii\console\Controller;

use app\models\TunjanganInstansiJenisJabatanKelas;

/**
 * Perintah konsol ini digunakan untuk membantu sinkronisasi data.
 */
class TestController extends Controller
{
    public function actionPotongan()
    {
        foreach (\app\models\Pegawai::find()->all() as $pegawai) {
            $potongan = $pegawai->calculatePotongan(1);
            $this->stdout("$pegawai->nip $pegawai->nama potongan : $potongan->jumlah_persen_potongan\n");
        }
    }

    public function actionTest()
    {
        $lorem = 'Fugiat cupidatat laborum dolore magna incididunt ex commodo ex ea in aliquip occaecat cupidatat sed quis magna laborum minim id reprehenderit dolore non est minim.';

        $this->stdout("$lorem");
    }

    public function actionPersentase()
    {
        $no = 0;

        foreach (TunjanganInstansiJenisJabatanKelas::find()->all() as $data) {
            if ($data->id_jenis_jabatan == 1 && $data->kelas_jabatan == 16){
                $data->updateAttributes([
                    'beban_kerja_persen' => 32,
                    'prestasi_kerja_persen' => 17,
                    'kondisi_kerja_persen' => 17,
                    'tempat_bertugas_persen' => 17,
                    'kelangkaan_profesi_persen' => 17,
                ]);
                $no++;
                $this->stdout("$no. OK\n");
            }

            if ($data->id_jenis_jabatan == 1 && $data->kelas_jabatan == 15){
                $data->updateAttributes([
                    'beban_kerja_persen' => 27,
                    'prestasi_kerja_persen' => 27,
                    'kondisi_kerja_persen' => 23,
                    'tempat_bertugas_persen' => 23,
                    'kelangkaan_profesi_persen' => 0,
                ]);
                $no++;
                $this->stdout("$no. OK\n");
            }

            if ($data->id_jenis_jabatan == 1 && $data->kelas_jabatan == 14){
                $data->updateAttributes([
                    'beban_kerja_persen' => 33,
                    'prestasi_kerja_persen' => 33,
                    'kondisi_kerja_persen' => 17,
                    'tempat_bertugas_persen' => 17,
                    'kelangkaan_profesi_persen' => 0,
                ]);
                $no++;
                $this->stdout("$no. OK\n");
            }

            if ($data->id_jenis_jabatan == 1 && $data->kelas_jabatan == 13){
                $data->updateAttributes([
                    'beban_kerja_persen' => 27,
                    'prestasi_kerja_persen' => 31,
                    'kondisi_kerja_persen' => 21,
                    'tempat_bertugas_persen' => 21,
                    'kelangkaan_profesi_persen' => 0,
                ]);
                $no++;
                $this->stdout("$no. OK\n");
            }

            if ($data->id_jenis_jabatan == 1 && $data->kelas_jabatan == 12){
                $data->updateAttributes([
                    'beban_kerja_persen' => 27,
                    'prestasi_kerja_persen' => 26,
                    'kondisi_kerja_persen' => 21,
                    'tempat_bertugas_persen' => 26,
                    'kelangkaan_profesi_persen' => 0,
                ]);
                $no++;
                $this->stdout("$no. OK\n");
            }

            if ($data->id_jenis_jabatan == 1 && $data->kelas_jabatan == 11){
                $data->updateAttributes([
                    'beban_kerja_persen' => 32,
                    'prestasi_kerja_persen' => 32,
                    'kondisi_kerja_persen' => 16,
                    'tempat_bertugas_persen' => 20,
                    'kelangkaan_profesi_persen' => 0,
                ]);
                $no++;
                $this->stdout("$no. OK\n");
            }

            if ($data->id_jenis_jabatan == 1 && $data->kelas_jabatan == 9){
                $data->updateAttributes([
                    'beban_kerja_persen' => 24,
                    'prestasi_kerja_persen' => 33,
                    'kondisi_kerja_persen' => 19,
                    'tempat_bertugas_persen' => 24,
                    'kelangkaan_profesi_persen' => 0,
                ]);
                $no++;
                $this->stdout("$no. OK\n");
            }

            if ($data->id_jenis_jabatan == 1 && $data->kelas_jabatan == 8){
                $data->updateAttributes([
                    'beban_kerja_persen' => 27,
                    'prestasi_kerja_persen' => 31,
                    'kondisi_kerja_persen' => 23,
                    'tempat_bertugas_persen' => 19,
                    'kelangkaan_profesi_persen' => 0,
                ]);
                $no++;
                $this->stdout("$no. OK\n");
            }

            if ($data->id_jenis_jabatan == 3 && $data->kelas_jabatan == 7){
                $data->updateAttributes([
                    'beban_kerja_persen' => 22,
                    'prestasi_kerja_persen' => 21,
                    'kondisi_kerja_persen' => 27,
                    'tempat_bertugas_persen' => 30,
                    'kelangkaan_profesi_persen' => 0,
                ]);
                $no++;
                $this->stdout("$no. OK\n");
            }

            if ($data->id_jenis_jabatan == 3 && $data->kelas_jabatan == 6){
                $data->updateAttributes([
                    'beban_kerja_persen' => 19,
                    'prestasi_kerja_persen' => 22,
                    'kondisi_kerja_persen' => 28,
                    'tempat_bertugas_persen' => 31,
                    'kelangkaan_profesi_persen' => 0,
                ]);
                $no++;
                $this->stdout("$no. OK\n");
            }

            if ($data->id_jenis_jabatan == 3 && $data->kelas_jabatan == 5){
                $data->updateAttributes([
                    'beban_kerja_persen' => 24,
                    'prestasi_kerja_persen' => 35,
                    'kondisi_kerja_persen' => 14,
                    'tempat_bertugas_persen' => 27,
                    'kelangkaan_profesi_persen' => 0,
                ]);
                $no++;
                $this->stdout("$no. OK\n");
            }

            if ($data->id_jenis_jabatan == 3 && $data->kelas_jabatan == 3){
                $data->updateAttributes([
                    'beban_kerja_persen' => 28,
                    'prestasi_kerja_persen' => 29,
                    'kondisi_kerja_persen' => 29,
                    'tempat_bertugas_persen' => 14,
                    'kelangkaan_profesi_persen' => 0,
                ]);
                $no++;
                $this->stdout("$no. OK\n");
            }

            if ($data->id_jenis_jabatan == 3 && $data->kelas_jabatan == 1){
                $data->updateAttributes([
                    'beban_kerja_persen' => 33,
                    'prestasi_kerja_persen' => 27,
                    'kondisi_kerja_persen' => 27,
                    'tempat_bertugas_persen' => 13,
                    'kelangkaan_profesi_persen' => 0,
                ]);
                $no++;
                $this->stdout("$no. OK\n");
            }

            if ($data->id_jenis_jabatan == 2 && $data->kelas_jabatan == 15){
                $data->updateAttributes([
                    'beban_kerja_persen' => 22,
                    'prestasi_kerja_persen' => 21,
                    'kondisi_kerja_persen' => 21,
                    'tempat_bertugas_persen' => 36,
                    'kelangkaan_profesi_persen' => 0,
                ]);
                $no++;
                $this->stdout("$no. OK\n");
            }

            if ($data->id_jenis_jabatan == 2 && $data->kelas_jabatan == 13){
                $data->updateAttributes([
                    'beban_kerja_persen' => 24,
                    'prestasi_kerja_persen' => 19,
                    'kondisi_kerja_persen' => 19,
                    'tempat_bertugas_persen' => 38,
                    'kelangkaan_profesi_persen' => 0,
                ]);
                $no++;
                $this->stdout("$no. OK\n");
            }

            if ($data->id_jenis_jabatan == 2 && $data->kelas_jabatan == 12){
                $data->updateAttributes([
                    'beban_kerja_persen' => 24,
                    'prestasi_kerja_persen' => 25,
                    'kondisi_kerja_persen' => 19,
                    'tempat_bertugas_persen' => 32,
                    'kelangkaan_profesi_persen' => 0,
                ]);
                $no++;
                $this->stdout("$no. OK\n");
            }

            if ($data->id_jenis_jabatan == 2 && $data->kelas_jabatan == 11){
                $data->updateAttributes([
                    'beban_kerja_persen' => 30,
                    'prestasi_kerja_persen' => 30,
                    'kondisi_kerja_persen' => 15,
                    'tempat_bertugas_persen' => 25,
                    'kelangkaan_profesi_persen' => 0,
                ]);
                $no++;
                $this->stdout("$no. OK\n");
            }

            if ($data->id_jenis_jabatan == 2 && $data->kelas_jabatan == 10){
                $data->updateAttributes([
                    'beban_kerja_persen' => 25,
                    'prestasi_kerja_persen' => 29,
                    'kondisi_kerja_persen' => 17,
                    'tempat_bertugas_persen' => 29,
                    'kelangkaan_profesi_persen' => 0,
                ]);
                $no++;
                $this->stdout("$no. OK\n");
            }

            if ($data->id_jenis_jabatan == 2 && $data->kelas_jabatan == 9){
                $data->updateAttributes([
                    'beban_kerja_persen' => 26,
                    'prestasi_kerja_persen' => 32,
                    'kondisi_kerja_persen' => 16,
                    'tempat_bertugas_persen' => 26,
                    'kelangkaan_profesi_persen' => 0,
                ]);
                $no++;
                $this->stdout("$no. OK\n");
            }

            if ($data->id_jenis_jabatan == 2 && $data->kelas_jabatan == 8){
                $data->updateAttributes([
                    'beban_kerja_persen' => 26,
                    'prestasi_kerja_persen' => 31,
                    'kondisi_kerja_persen' => 14,
                    'tempat_bertugas_persen' => 29,
                    'kelangkaan_profesi_persen' => 0,
                ]);
                $no++;
                $this->stdout("$no. OK\n");
            }

            if ($data->id_jenis_jabatan == 2 && $data->kelas_jabatan == 7){
                $data->updateAttributes([
                    'beban_kerja_persen' => 27,
                    'prestasi_kerja_persen' => 29,
                    'kondisi_kerja_persen' => 15,
                    'tempat_bertugas_persen' => 29,
                    'kelangkaan_profesi_persen' => 0,
                ]);
                $no++;
                $this->stdout("$no. OK\n");
            }

            if ($data->id_jenis_jabatan == 2 && $data->kelas_jabatan == 6){
                $data->updateAttributes([
                    'beban_kerja_persen' => 27,
                    'prestasi_kerja_persen' => 31,
                    'kondisi_kerja_persen' => 14,
                    'tempat_bertugas_persen' => 28,
                    'kelangkaan_profesi_persen' => 0,   
                ]);
                $no++;
                $this->stdout("$no. OK\n");
            }

            if ($data->id_jenis_jabatan == 2 && $data->kelas_jabatan == 5){
                $data->updateAttributes([
                    'beban_kerja_persen' => 24,
                    'prestasi_kerja_persen' => 35,
                    'kondisi_kerja_persen' => 14,
                    'tempat_bertugas_persen' => 27,
                    'kelangkaan_profesi_persen' => 0,
                ]);
                $no++;
                $this->stdout("$no. OK\n");
            }
        }
    }
}
