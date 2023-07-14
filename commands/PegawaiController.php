<?php

namespace app\commands;

use Yii;
use app\models\Instansi;
use app\models\InstansiPegawai;
use app\models\Pegawai;
use yii\console\Controller;
use yii\helpers\Console;
use yii\httpclient\Client;

/**
 * Perintah konsol ini digunakan untuk membantu sinkronisasi data.
 */
class PegawaiController extends Controller
{

    public static $gol = [
        1 => 'I/a',
        2 => 'I/b',
        3 => 'I/c',
        4 => 'I/d',
        5 => 'II/a',
        6 => 'II/b',
        7 => 'II/c',
        8 => 'II/d',
        9 => 'III/a',
        10 => 'III/b',
        11 => 'III/c',
        12 => 'III/d',
        13 => 'IV/a',
        14 => 'IV/b',
        15 => 'IV/c',
        16 => 'IV/d',
        17 => 'IV/e'
    ];

    public static $eselon = [
        1 => 'I-a',
        2 => 'I-b',
        3 => 'II-a',
        4 => 'II-b',
        5 => 'III-a',
        6 => 'III-b',
        7 => 'IV-a',
        8 => 'IV-b',
        9 => 'V-a',
        10 => 'Non Eselon'
    ];

    public function actionInit()
    {
        $inputFileName = 'data\data-desember.xlsx';
        if(!file_exists($inputFileName)){
            print 'data tidak ditemukan pada folder data : ';
            print $inputFileName;
            return null;
        }
        try {
            $inputFileType = \PHPExcel_IOFactory::identify($inputFileName);
            $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
            $objReader->setReadDataOnly(true);
            $PHPExcel = $objReader->load($inputFileName);
        } catch (Exception $e) {
            die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' .
            $e->getMessage());
        }

        $PHPExcel->setActiveSheetIndex(0);
        $sheet = $PHPExcel->getActiveSheet();
        $highestRow = $sheet->getHighestRow();
        $sukses = $gagal = [];
        $l = 0;
        for($i = 2; $i <= $highestRow; $i++) {
            $nip = trim($sheet->getCell('D' . $i)->getValue());
            $pegawai = Pegawai::findOne(['nip' => $nip]);
            if ($pegawai !== null) {
                $nama_jabatan = trim($sheet->getCell('H' . $i)->getValue());
                $golongan = trim($sheet->getCell('G' . $i)->getValue());
                // $pegawai->nama_jabatan = $nama_jabatan;
                $pegawai->id_golongan = $this->getGolongan($golongan);
                if ($pegawai->nama_jabatan != $nama_jabatan) {
                    $l++;
                    $this->stdout("\r$pegawai->nama jabatan $pegawai->nama_jabatan !== $nama_jabatan \n");
                }
                /*if ($pegawai->id_golongan === null) {
                    $this->stdout("\rPegawai $nip golongannya null\n", Console::FG_RED, Console::BOLD);
                }*/
            }
            $this->stdout("\r$i/$highestRow");
        }
        $this->stdout("\n$l");
        file_put_contents("logging" . time() . "_sukses.txt", json_encode($sukses));
        file_put_contents("logging" . time() . "_gagal.txt", json_encode($gagal));
    }

    public function getGolongan($value)
    {
        return array_search(trim($value), self::$gol);
    }

    public function actionEselon()
    {
        $i = 1;
        foreach (Pegawai::find()->all() as $pegawai) {
            if ($pegawai->eselon == null) {
                $this->stdout("$pegawai->nama adalah non eselon\n", Console::FG_YELLOW);
                continue;
            }
            if (strcmp('-', $pegawai->eselon)) {
                $pegawai->eselon = rtrim($pegawai->eselon);
                $pegawai->id_eselon = $this->getEselon($pegawai->eselon);
                if ($pegawai->validate()) {
                    // $this->stdout("$pegawai->nama telah diubah eselonnya jadi foreign key\n", Console::FG_GREEN);
                } else {
                    // $this->stdout("$pegawai->nama error \n", Console::FG_RED);
                }
                $i++;
            } else {
                $this->stdout("$pegawai->nama adalah non eselon\n", Console::FG_YELLOW);
            }
        }
        $this->stdout("$i lorem");
    }

    public function actionEselonExcel()
    {
        $inputFileName = 'data\data-desember.xlsx';
        if(!file_exists($inputFileName)){
            print 'data tidak ditemukan pada folder data : ';
            print $inputFileName;
            return null;
        }
        try {
            $inputFileType = \PHPExcel_IOFactory::identify($inputFileName);
            $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
            $objReader->setReadDataOnly(true);
            $PHPExcel = $objReader->load($inputFileName);
        } catch (Exception $e) {
            die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' .
            $e->getMessage());
        }

        $PHPExcel->setActiveSheetIndex(0);
        $sheet = $PHPExcel->getActiveSheet();
        $highestRow = $sheet->getHighestRow();
        $sukses = $gagal = [];
        for($i = 2; $i <= $highestRow; $i++) {
            $nip = trim($sheet->getCell('D' . $i)->getValue());
            $pegawai = Pegawai::findOne(['nip' => $nip]);
            if ($pegawai !== null) {
                $eselon = rtrim($sheet->getCell('F' . $i)->getValue());
                $pegawai->id_eselon = $this->getEselon($eselon);
                if ($pegawai->save(false)) {
                    $sukses[] = $pegawai->nip;
                } else {
                    $gagal[] = $pegawai->nip;
                }
                if ($pegawai->id_eselon === null) {
                    $this->stdout("\rPegawai $nip eselonnya null\n", Console::FG_RED, Console::BOLD);
                }
            } else {
                $this->stdout("Pegawai $nip tidak ada datanya\n");
            }
            $this->stdout("\r$i/$highestRow");
        }
        file_put_contents("logging" . time() . "_sukses.txt", json_encode($sukses));
        file_put_contents("logging" . time() . "_gagal.txt", json_encode($gagal));
    }

    public function getEselon($value)
    {
        return (int) array_search(trim($value), self::$eselon);
    }

    public function actionRefreshRekap($id_instansi, $bulan = null)
    {
        if ($bulan === null) {
            $bulan = date('m');
        }
        $instansi = Instansi::find()
            ->andWhere(['id' => $id_instansi])
            ->with(['manyPegawai.manyCheckinout'])
            ->one();
        $total = count($instansi->manyPegawai);
        $prog = 0;
        Console::startProgress($prog, $total, "Merefresh rekap absensi");
        foreach ($instansi->manyPegawai as $pegawai) {
            $pegawai->getPotonganBulan($bulan);
            $pegawai->updatePegawaiRekapAbsensi($bulan);
            Console::updateProgress(++$prog, $total);
        }
        Console::endProgress();
    }

    public function actionRefreshPegawaiRekapAbsensi($bulan, $tahun, $offset = 0)
    {
        $query = Pegawai::find();
        $query->offset($offset);
        $allPegawai = $query->all();
        $total = count($allPegawai);
        $prog = 0;
        Console::startProgress($prog, $total, "Merefresh rekap absensi");
        foreach ($allPegawai as $pegawai) {
            //$pegawai->getPotonganBulan($bulan, $tahun);
            $pegawai->updatePegawaiRekapAbsensi($bulan, $tahun);
            Console::updateProgress(++$prog, $total);
        }
        Console::endProgress();
    }

    public function actionMonitorInstansiPegawai()
    {
        $query = Pegawai::find();

        foreach($query->all() as $pegawai) {
            $count = $pegawai->getManyInstansiPegawai()->count();
            $this->stdout("$pegawai->id - $pegawai->nama - $pegawai->nip : Jumlah instansi_pegawai = $count \n");

        }
    }

    public function actionRefreshInstansiPegawaiSkp($tahun)
    {
        $query = Pegawai::find();

        $done = 0;
        $total = $query->count();

        Console::startProgress($done,$total);

        foreach($query->all() as $pegawai) {
            /* @var $pegawai \app\models\Pegawai */
            $pegawai->refreshInstansiPegawaiSkp(['tahun'=>$tahun]);
            Console::updateProgress($done++,$total);
        }

        Console::endProgress();
    }

    public function actionCountInstansiPegawaiSkp()
    {
        $query = Pegawai::find();
        $query->with(['manyInstansiPegawaiSkp']);

        foreach($query->all() as $data) {
            $jumlah = count($data->manyInstansiPegawaiSkp);

            if($jumlah > 1) {
                $this->stdout("ID : $data->id; Nama : $data->nama; Jumlah SKP: $jumlah".PHP_EOL);
            }


        }
    }

    public function actionRefreshIdJabatanInstansiPegawai()
    {
        $query = Pegawai::find();
        $total = $query->count();
        $done = 0;

        Console::startProgress($done,$total);
        foreach($query->all() as $pegawai) {
            $instansiPegawai = $pegawai->getManyInstansiPegawai()->orderBy(['tanggal_berlaku'=>SORT_DESC])->one();
            if($instansiPegawai!==null) {
                $instansiPegawai->updateAttributes([
                   'id_jabatan'=>$pegawai->id_jabatan
                ]);
            }

            Console::updateProgress($done++,$total);
        }

        Console::endProgress();
    }

    public function actionUpdateNik()
    {
        $url = @Yii::$app->params['url_simadig'];
        $url .= '/api/pegawai/view/';

        $query = Pegawai::find();
        $query->andWhere(['status_update_nik' => 0]);

        $total = $query->count();
        $done = 0;

        Console::startProgress($done,$total);
        foreach ($query->all() as $pegawai) {
            $client = new Client();
            $response = $client->createRequest()
                ->setMethod('GET')
                ->setUrl($url.$pegawai->nip)
                ->send();

            if ($response->statusCode == 429) {
                print_r('429 | TOO MANY REQUESTS');die;
            }
            
            if ($response->statusCode != 200) {
                $pegawai->updateAttributes([
                    'status_update_nik' => 1,
                ]);
                Console::updateProgress($done++,$total);
                continue;
            }
            
            $responseJson = json_decode($response->content);

            $pegawai->updateAttributes([
                'nik' => $responseJson->nik,
                'status_update_nik' => 1,
            ]);

            Console::updateProgress($done++,$total);
        }
        Console::endProgress();
    }

    public function actionGenerateInstansiPegawai2022()
    {
        $query = Pegawai::find();

        $total = $query->count();
        $done = 0;

        Console::startProgress($done,$total);
        foreach ($query->all() as $pegawai) {
            $instansiPegawai = $pegawai->instansiPegawaiBerlaku;
            $jabatan = @$instansiPegawai->jabatan;

            if ($jabatan != null
                AND $jabatan->nama_2021 != null 
                AND $jabatan->nama_2022 != null
            ) {
                if ($instansiPegawai->nama_jabatan != $jabatan->nama_2022
                    AND $jabatan->nama_2021 != $jabatan->nama_2022
                ) {
                    
                    $model = new InstansiPegawai();
                    $model->id_instansi = $instansiPegawai->id_instansi;
                    $model->id_pegawai = $instansiPegawai->id_pegawai;
                    $model->id_jabatan = $instansiPegawai->id_jabatan;
                    $model->tanggal_berlaku = '2022-01-01';

                    $model->setTanggalMulai();
                    $model->setTanggalSelesai();

                    $model->nama_jabatan = $model->getNamaJabatan();
                    $model->nama_jabatan_atasan = $model->getNamaJabatanAtasan();
                    $model->nama_instansi = @$model->instansi->nama;

                    if ($model->save()) {
                        $model->updateMundurTanggalSelesai();
                    }
                }
            }

            $done++;
            Console::updateProgress($done,$total);
        }
        Console::endProgress();
    }

    public function actionCekJumlahSudahPenyetaraan()
    {
        $query = Pegawai::find();

        $jumlah = 1;
        foreach ($query->all() as $pegawai) {
            $instansiPegawai = $pegawai->instansiPegawaiBerlaku;
            $jabatan = @$instansiPegawai->jabatan;
            
            if ($instansiPegawai != null AND $jabatan != null) {
                if ($instansiPegawai->tanggal_mulai == '2022-01-01'
                    AND $jabatan->nama_2021 != null 
                    AND $jabatan->nama_2022 != null
                    AND $jabatan->nama_2021 != $jabatan->nama_2022
                    AND $instansiPegawai->nama_jabatan == $jabatan->nama_2022
                ) {
                    print("$jumlah -> {$instansiPegawai->pegawai->nama} ({$instansiPegawai->pegawai->nip}) \n");
                    $jumlah++;
                }
            }
        }
    }
}
