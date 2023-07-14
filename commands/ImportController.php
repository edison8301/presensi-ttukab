<?php
/**
 * Created by PhpStorm.
 * User: iqbal
 * Date: 10/11/2018
 * Time: 10:36 AM
 */

namespace app\commands;

use PhpOffice\PhpSpreadsheet\IOFactory;
use app\models\Golongan;
use app\models\Instansi;
use app\models\InstansiPegawai;
use app\models\Pegawai;
use app\models\PegawaiGolongan;
use app\modules\absensi\models\Jabatan;
use app\modules\kinerja\models\KegiatanTahunan;
use yii\console\Controller;
use yii\helpers\Console;

class ImportController extends Controller
{
    public $defaultAction = 'tukin';

    public function actionTukin()
    {
        $fileDir = 'data/tukin/LAMPIRAN II.xlsx';
        if (!file_exists($fileDir)) {
            $this->stdout('File tidak ditemukan : ' . pathinfo($fileDir, PATHINFO_BASENAME), Console::FG_RED, Console::BOLD);
            return 0;
        }
        $reader = IOFactory::load($fileDir);
        $reader->setActiveSheetIndex(0);
        $sheet = $reader->getActiveSheet();
        $instansi = null;
        $bidang = null;
        /*for ($i = 19; $i <= 171; $i++) {
            $isMerged = $sheet->getCell("B$i")->isInMergeRange();
            if ($isMerged) {
                $cellAValue = trim($sheet->getCell("A$i")->getValue());
                if (strcasecmp(substr($cellAValue, 0, 4), 'biro') === 0) {
                    $instansi = Instansi::findOne(['nama' => $cellAValue]);
                    if ($instansi === null) {
                        while (true) {
                            $this->stdout("Instansi $cellAValue tidak ditemukan " . PHP_EOL, Console::FG_RED, Console::BOLD);
                            $id_instansi = Console::input("Masukkan id instansi : ");
                            $instansi = Instansi::findOne($id_instansi);
                            if ($instansi !== null) {
                                if (Console::confirm("Instansi yang dipilih : $instansi->nama")) {
                                    break;
                                }
                            } else {
                                $this->stderr("Instansi dengan id $id_instansi tidak ditemukan!" . PHP_EOL, Console::FG_RED, Console::UNDERLINE);
                            }
                        }
                    }
                    $this->stdout("Instansi : $instansi->nama" . PHP_EOL, Console::FG_GREEN, Console::BOLD);
                    $bidang = null;
                } else {
                    $bidang = $cellAValue;
                    $this->stdout("\tBidang : $bidang" . PHP_EOL, Console::FG_BLUE, Console::BOLD);
                }
            } else {
                $namaJabatan = $sheet->getCell("B$i")->getValue();
                $kelasJabatan = $sheet->getCell("C$i")->getValue();
                $persediaan = $sheet->getCell("D$i")->getValue();
                $jabatan = new Jabatan([
                    'id_instansi' => $instansi->id,
                    'nama' => $namaJabatan,
                    'id_jenis_jabatan' => 1,
                    'bidang' => $bidang,
                    'kelas_jabatan' => $kelasJabatan,
                    'persediaan_pegawai' => $persediaan,
                ]);
                $jabatan->save(false);
                $this->stdout("\t\t\tJabatan $namaJabatan imported" . PHP_EOL, Console::UNDERLINE);
            }
        }*/

        // Dinas Utama dan Badan
        for ($i = 182; $i <= 1117; $i++) {
            $isMerged = $sheet->getCell("B$i")->isInMergeRange();
            $cellAValue = trim($sheet->getCell("A$i")->getValue());
            if ($isMerged) {
                if (strpos($cellAValue, '.') !== false) {
                    $search = trim(explode('.', $cellAValue)[1]);
                    $instansi = Instansi::findOne(['nama' => $search]);
                    if ($instansi === null) {
                        while (true) {
                            $this->stdout("Instansi $cellAValue tidak ditemukan " . PHP_EOL, Console::FG_RED, Console::BOLD);
                            $id_instansi = Console::input("Masukkan id instansi : ");
                            $instansi = Instansi::findOne($id_instansi);
                            if ($instansi !== null) {
                                if (Console::confirm("Instansi yang dipilih : $instansi->nama")) {
                                    break;
                                }
                            } else {
                                $this->stderr("Instansi dengan id $id_instansi tidak ditemukan!" . PHP_EOL, Console::FG_RED, Console::UNDERLINE);
                            }
                        }
                    }
                    $this->stdout("Instansi : $instansi->nama" . PHP_EOL, Console::FG_GREEN, Console::BOLD);
                    $bidang = null;
                    $subbidang = null;
                    $id_jenis_jabatan = null;
                } else {
                    $bidang = $cellAValue;
                    $this->stdout("\tBidang : $bidang" . PHP_EOL, Console::FG_BLUE, Console::BOLD);
                }
            } else {
                $namaJabatan = $sheet->getCell("B$i")->getValue();
                $kelasJabatan = (int) $sheet->getCell("C$i")->getValue();
                $persediaan = $sheet->getCell("D$i")->getValue();
                $jabatan = new Jabatan([
                    'id_instansi' => $instansi->id,
                    'nama' => $namaJabatan,
                    'id_jenis_jabatan' => 1,
                    'bidang' => $bidang,
                    'kelas_jabatan' => $kelasJabatan,
                    'persediaan_pegawai' => $persediaan,
                ]);
                $jabatan->save(false);
                $this->stdout("\t\t\tJabatan $namaJabatan imported" . PHP_EOL, Console::UNDERLINE);
            }
        }
    }

    public function actionFungsional()
    {
        $fileDir = 'data/tukin/LAMPIRAN III.xlsx';
        if (!file_exists($fileDir)) {
            $this->stdout('File tidak ditemukan : ' . pathinfo($fileDir, PATHINFO_BASENAME), Console::FG_RED, Console::BOLD);
            return 0;
        }
        $reader = IOFactory::load($fileDir);
        $reader->setActiveSheetIndex(0);
        $sheet = $reader->getActiveSheet();
        $instansi = null;
        $bidang = null;
        $subbidang = null;
        $id_jenis_jabatan = null;
        // Biro
        for ($i = 10; $i <= 282; $i++) {
            $isMerged = $sheet->getCell("B$i")->isInMergeRange();
            if ($isMerged) {
                $cellA = $sheet->getCell("A$i");
                $cellAValue = trim($sheet->getCell("A$i")->getValue());
                if (strcasecmp(substr($cellAValue, 0, 4), 'biro') === 0) {
                    $instansi = Instansi::findOne(['nama' => $cellAValue]);
                    if ($instansi === null) {
                        while (true) {
                            $this->stdout("Instansi $cellAValue tidak ditemukan " . PHP_EOL, Console::FG_RED, Console::BOLD);
                            $id_instansi = Console::input("Masukkan id instansi : ");
                            $instansi = Instansi::findOne($id_instansi);
                            if ($instansi !== null) {
                                if (Console::confirm("Instansi yang dipilih : $instansi->nama")) {
                                    break;
                                }
                            } else {
                                $this->stderr("Instansi dengan id $id_instansi tidak ditemukan!" . PHP_EOL, Console::FG_RED, Console::UNDERLINE);
                            }
                        }
                    }
                    $this->stdout("Instansi : $instansi->nama" . PHP_EOL, Console::FG_GREEN, Console::BOLD);
                    $bidang = null;
                    $subbidang = null;
                    $id_jenis_jabatan = null;
                } elseif ($cellA->getStyle()->getFont()->getBold() === true) {
                    $bidang = $cellAValue;
                    $this->stdout("\tBidang : $bidang" . PHP_EOL, Console::FG_BLUE, Console::BOLD);
                } else {
                    if (stripos($cellAValue, 'fungsional') !== false) {
                        $subbidang = 'Fungsional';
                        $id_jenis_jabatan = 3;
                    } else {
                        $subbidang = $cellAValue;
                        $id_jenis_jabatan = 2;
                    }
                    $this->stdout("\t\tSub Bidang : $subbidang" . PHP_EOL, Console::FG_CYAN, Console::BOLD);
                }
            } else {
                $namaJabatan = $sheet->getCell("B$i")->getValue();
                $kelasJabatan = (int) $sheet->getCell("C$i")->getValue();
                $persediaan = $sheet->getCell("D$i")->getValue();
                $jabatan = new Jabatan([
                    'id_instansi' => $instansi->id,
                    'nama' => $namaJabatan,
                    'id_jenis_jabatan' => $id_jenis_jabatan,
                    'bidang' => $bidang,
                    'subbidang' => $subbidang,
                    'kelas_jabatan' => $kelasJabatan,
                    'persediaan_pegawai' => $persediaan,
                ]);
                $jabatan->save(false);
                $this->stdout("\t\t\tJabatan $namaJabatan imported" . PHP_EOL, Console::UNDERLINE);
            }
        }
        // Dinas Utama dan Badan
        for ($i = 283; $i <= 2497; $i++) {
            $isMerged = $sheet->getCell("B$i")->isInMergeRange();
            $cellA = $sheet->getCell("A$i");
            $cellAValue = trim($sheet->getCell("A$i")->getValue());
            if ($isMerged) {
                if (strpos($cellAValue, '.') !== false) {
                    $search = trim(explode('.', $cellAValue)[1]);
                    $instansi = Instansi::findOne(['nama' => $search]);
                    if ($instansi === null) {
                        while (true) {
                            $this->stdout("Instansi $cellAValue tidak ditemukan " . PHP_EOL, Console::FG_RED, Console::BOLD);
                            $id_instansi = Console::input("Masukkan id instansi : ");
                            $instansi = Instansi::findOne($id_instansi);
                            if ($instansi !== null) {
                                if (Console::confirm("Instansi yang dipilih : $instansi->nama")) {
                                    break;
                                }
                            } else {
                                $this->stderr("Instansi dengan id $id_instansi tidak ditemukan!" . PHP_EOL, Console::FG_RED, Console::UNDERLINE);
                            }
                        }
                    }
                    $this->stdout("Instansi : $instansi->nama" . PHP_EOL, Console::FG_GREEN, Console::BOLD);
                    $bidang = null;
                    $subbidang = null;
                    $id_jenis_jabatan = null;
                } elseif ($cellA->getStyle()->getFont()->getBold() === true) {
                    $bidang = $cellAValue;
                    $this->stdout("\tBidang : $bidang" . PHP_EOL, Console::FG_BLUE, Console::BOLD);
                } else {
                    if (stripos($cellAValue, 'fungsional') !== false) {
                        $subbidang = 'Fungsional';
                        $id_jenis_jabatan = 3;
                    } else {
                        $subbidang = $cellAValue;
                        $id_jenis_jabatan = 2;
                    }
                    $this->stdout("\t\tSub Bidang : $subbidang" . PHP_EOL, Console::FG_CYAN, Console::BOLD);
                }
            } else {
                if (stripos($cellAValue, 'fungsional') !== false) {
                    $subbidang = 'Fungsional';
                    $id_jenis_jabatan = 3;
                    continue;
                }
                $namaJabatan = $sheet->getCell("B$i")->getValue();
                $kelasJabatan = (int) $sheet->getCell("C$i")->getValue();
                $persediaan = $sheet->getCell("D$i")->getValue();
                $jabatan = new Jabatan([
                    'id_instansi' => $instansi->id,
                    'nama' => $namaJabatan,
                    'id_jenis_jabatan' => $id_jenis_jabatan,
                    'bidang' => $bidang,
                    'subbidang' => $subbidang,
                    'kelas_jabatan' => $kelasJabatan,
                    'persediaan_pegawai' => $persediaan,
                ]);
                $jabatan->save(false);
                $this->stdout("\t\t\tJabatan $namaJabatan imported" . PHP_EOL, Console::UNDERLINE);
            }
        }
    }

    public function actionMapJabatan()
    {
        foreach (Pegawai::find()->with('instansiPegawaiTahun')->limit(50)->aktif()->all() as $pegawai) {
            $this->stdout("Pegawai : $pegawai->nama" . PHP_EOL, Console::UNDERLINE);
            foreach ($pegawai->instansiPegawaiTahun as $instansiPegawai) {
                /** @var InstansiPegawai $instansiPegawai */
                $ref = Jabatan::find()
                    ->andWhere(['id_instansi' => $instansiPegawai->id_instansi])
                    ->andWhere(['like', 'nama', $instansiPegawai->nama_jabatan])
                    ->all();
                $this->stdout("\tJabatan : $instansiPegawai->nama_jabatan " . PHP_EOL, Console::FG_YELLOW, Console::BOLD);
                if ($ref !== []) {
                    foreach ($ref as $item) {
                        if (strcasecmp(trim($instansiPegawai->nama_jabatan), trim($item->nama)) === 0) {
                            $instansiPegawai->id_ref_jabatan = $item->id;
                            $this->stdout("\t\tCocok : Jabatan $instansiPegawai->nama_jabatan di bidang $item->bidang dan subbidang $item->subbidang" . PHP_EOL, Console::FG_GREEN, Console::BOLD);
                        } else {
                            $this->stdout("\t\tMendekati : Jabatan $instansiPegawai->nama_jabatan di bidang $item->bidang dan subbidang $item->subbidang dengan $item->nama" . PHP_EOL, Console::FG_CYAN, Console::BOLD);
                        }
                    }
                } else {
                    $this->stdout("\t\tTidak ditemukan : Jabatan $instansiPegawai->nama_jabatan" . PHP_EOL, Console::FG_RED, Console::BOLD);
                }
            }
        }
    }

    public function actionKegiatanHarian()
    {
        $query = KegiatanTahunan::find()
            ->joinWith([
                    'induk' => function ($query) {
                        $query->from(['induk' => KegiatanTahunan::tableName()]);
                    },
                ]
            )
            ->andWhere(['induk.status_hapus' => true])
            ->andWhere('kegiatan_tahunan.id_induk IS NOT NULL')
            ->aktif();
        foreach ($query->all() as $kegiatanTahunan) {
            $count = count($kegiatanTahunan->allKegiatanHarian);
            if (empty($count)) {
                $kegiatanTahunan->updateAttributes(['status_hapus' => true]);
                $this->stdout(str_replace(PHP_EOL, '',"$kegiatanTahunan->nama DELETED ") . PHP_EOL, Console::FG_RED, Console::BOLD);
            } else {
                $this->stdout(str_replace(PHP_EOL, '',"$kegiatanTahunan->nama KEPT : $count CKHP") . PHP_EOL, Console::FG_GREEN, Console::BOLD);
            }
        }
    }

    public function actionGolonganPegawai()
    {
        $fileDir = 'data/golongan/golongan.xls';
        if (!file_exists($fileDir)) {
            $this->stdout('File tidak ditemukan : ' . pathinfo($fileDir, PATHINFO_BASENAME), Console::FG_RED, Console::BOLD);
            return 0;
        }
        $reader = IOFactory::load($fileDir);
        $reader->setActiveSheetIndex(0);
        $sheet = $reader->getActiveSheet();
        $instansi = null;
        $bidang = null;
        $subbidang = null;
        $id_jenis_jabatan = null;
        
        $tidakDitemukan = [];
        for ($i = 8; $i <= 5473; $i++) {
            $nip = $sheet->getCell("B$i")->getValue();
            $nama = $sheet->getCell("C$i")->getValue();

            $golongan = $sheet->getCell("M$i")->getValue();
            $tmt_golongan = $sheet->getCell("N$i")->getValue();
            $instansi = $sheet->getCell("O$i")->getValue();

            $arr = explode('-', $tmt_golongan);
            $tmt_golongan = $arr[2].'-'.$arr[1].'-'.$arr[0];

            $this->stdout($tmt_golongan . PHP_EOL, Console::FG_GREEN, Console::BOLD);

            $pegawai = Pegawai::findOne(['nip' => $nip]);

            if ($pegawai !== null) {
                $this->setGolonganPegawai($pegawai, $golongan, $tmt_golongan);
                $this->stdout("NIP : ".$nip . PHP_EOL, Console::FG_GREEN, Console::BOLD);
            } else {
                $tidakDitemukan[] = $nama.' - '.$nip.' - '.$instansi;
                $this->stdout($nama ." TIDAK DITEMUKAN .". PHP_EOL, Console::FG_RED, Console::BOLD);
            }
        }
        $this->stdout("TOTAL TIDAK DITEMUKAN :".count($tidakDitemukan). PHP_EOL, Console::FG_RED, Console::BOLD);
        $no=1;
        foreach ($tidakDitemukan as $key => $value) {
            $this->stdout($no++." - ".$value. PHP_EOL, Console::FG_RED, Console::BOLD);
        }
    }

    public function setGolonganPegawai(Pegawai $pegawai, $golongan, $tmt_golongan)
    {
        $golongan = Golongan::findOne(['golongan' => $golongan]);

        if ($golongan !== null) {
            $golongan = $golongan->id;
        } else {
            echo $golongan.' Tidak Ditemukan !';
            die;
        }
        $pegawaiGolongan = new PegawaiGolongan([
            'id_pegawai' => $pegawai->id,
            'id_golongan' => $golongan,
            'tanggal_berlaku' => $tmt_golongan,
            'tanggal_mulai' => $tmt_golongan,
            'tanggal_selesai' => '9999-12-31',
        ]);

        if (!$pegawaiGolongan->save()) {
            $this->stdout('GAGAL : '.$pegawai->nama. PHP_EOL, Console::FG_RED, Console::BOLD);
            print_r($pegawaiGolongan->getErrors());
            die;
        }
    }
}
