<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use Yii;
use app\models\Pegawai;
use app\models\Instansi;
use app\models\JabatanEvjab;
use app\models\InstansiBidang;
use yii\console\Controller;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class JabatanEvjabController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex($message = 'hello world')
    {
        $this->stdout($message);
    }

    public function actionStruktural($message = 'hello world')
    {
        $fileDir = Yii::getAlias('@app') . '/data/tukin-evjab-struktural.xlsx';
        
        if (!file_exists($fileDir)) {
            echo 'File Tidak Ditemukan ' . pathinfo($fileDir, PATHINFO_BASENAME);
            die;
        }

        $reader = IOFactory::load($fileDir);
        $reader->setActiveSheetIndex(0);
        $sheet = $reader->getActiveSheet();
        $highestRow = 1040;

        for ($i = 11; $i <= $highestRow; $i++) {
            
            $no = trim($sheet->getCell("A$i")->getCalculatedValue());
            $jabatan = trim($sheet->getCell("B$i")->getValue());
            $kelas_jabatan = trim($sheet->getCell("C$i")->getCalculatedValue());
            $nilai_jabatan = trim($sheet->getCell("D$i")->getCalculatedValue());
            // $faktor1_kiri = trim($sheet->getCell("E$i")->getValue());
            // $faktor1_kanan = trim($sheet->getCell("F$i")->getCalculatedValue());
            // $faktor2_kiri = trim($sheet->getCell("G$i")->getValue());
            // $faktor2_kanan = trim($sheet->getCell("H$i")->getCalculatedValue());
            // $faktor3_kiri = trim($sheet->getCell("I$i")->getValue());
            // $faktor3_kanan = trim($sheet->getCell("J$i")->getCalculatedValue());
            // $faktor4_sifat_kiri = trim($sheet->getCell("K$i")->getValue());
            // $faktor4_sifat_kanan = trim($sheet->getCell("L$i")->getCalculatedValue());
            // $faktor4_tujuan_kiri = trim($sheet->getCell("M$i")->getValue());
            // $faktor4_tujuan_kanan = trim($sheet->getCell("N$i")->getCalculatedValue());
            // $faktor5_kiri = trim($sheet->getCell("O$i")->getValue());
            // $faktor5_kanan = trim($sheet->getCell("P$i")->getCalculatedValue());
            // $faktor6_kiri = trim($sheet->getCell("R$i")->getValue());
            // $faktor6_kanan = trim($sheet->getCell("S$i")->getCalculatedValue());

            if ($no == '') {

                $jabatan_array = [];
                $jabatan_array = (explode(".",$jabatan));

                if (@$jabatan_array[1] == null){
                    $nama_instansi = trim($jabatan_array[0]);
                } else {
                    $nama_instansi = trim($jabatan_array[1]);
                }

                $query = Instansi::find();
                $query->andWhere(['nama' => $nama_instansi]);
                $model = $query->one();

                //$this->stdout($nama_instansi.' - '.@$model->id . "\n");
                
            }

            else {
                //$this->stdout($model->id . ' - ' . $jabatan. "\n");

                $id_jenis_jabatan = 1;

                $jabatan_evjab = new JabatanEvjab();
                $jabatan_evjab->id_jenis_jabatan = $id_jenis_jabatan;
                $jabatan_evjab->id_instansi = $model->id;
                $jabatan_evjab->nama = $jabatan;
                $jabatan_evjab->nilai_jabatan = $nilai_jabatan;
                $jabatan_evjab->kelas_jabatan = $kelas_jabatan;
                $jabatan_evjab->nomor = $no;

                $jabatan_evjab->save();
            }
        }
    }

    public function actionNonStruktural($message = 'hello world')
    {
        $fileDir = Yii::getAlias('@app') . '/data/tukin-evjab-non-struktural.xlsx';
        
        if (!file_exists($fileDir)) {
            echo 'File Tidak Ditemukan ' . pathinfo($fileDir, PATHINFO_BASENAME);
            die;
        }

        $reader = IOFactory::load($fileDir);
        $reader->setActiveSheetIndex(0);
        $sheet = $reader->getActiveSheet();
        $highestRow = 2384;

        for ($i = 10; $i <= $highestRow; $i++) {
            
            $no = trim($sheet->getCell("A$i")->getCalculatedValue());
            $jabatan = trim($sheet->getCell("B$i")->getValue());
            $kelas_jabatan = trim($sheet->getCell("C$i")->getCalculatedValue());
            $nilai_jabatan = trim($sheet->getCell("D$i")->getCalculatedValue());

            if ($no == '') {

                $jabatan_array = [];
                $jabatan_array = (explode(".",$jabatan));

                if (@$jabatan_array[1] == null){
                    if (strpos($jabatan_array[0], 'Bagian') !== false OR 
                        strpos($jabatan_array[0], 'BAGIAN') !== false OR
                        strpos($jabatan_array[0], 'Bidang') !== false OR
                        strpos($jabatan_array[0], 'BIDANG') !== false OR
                        strpos($jabatan_array[0], 'Irban') !== false OR
                        strpos($jabatan_array[0], 'SEKRETARIAT') !== false OR
                        strpos($jabatan_array[0], 'Sekretariat') !== false){
                        $bagian = $jabatan_array[0];
                    } else {
                        $nama_instansi = trim($jabatan_array[0]);
                        $bagian = null;
                    }
                } else {
                    $nama_instansi = trim($jabatan_array[1]);
                    $bagian = null;
                }

                if ($bagian == null){
                    $query = Instansi::find();
                    $query->andWhere(['nama' => $nama_instansi]);
                    $model = $query->one();
                    $modelBidang = null;
                    //$this->stdout($nama_instansi.' - '.$model->id . "\n");            
                }
                else {
                    // Untuk tambah Instansi Bidang
                    /*$instansi_bidang = new InstansiBidang();
                    $instansi_bidang->id_instansi = $model->id;
                    $instansi_bidang->nama = $bagian;
                    $instansi_bidang->save();*/

                    $query_bidang = InstansiBidang::find();
                    $query_bidang->andWhere(['id_instansi' => $model->id]);
                    $query_bidang->andWhere(['nama' => $bagian]);
                    $modelBidang = $query_bidang->one();

                    /*$this->stdout('Instansi = '.$nama_instansi.' - '.$model->id . "\n");
                    $this->stdout('Bagian = '.$bagian . "\n\n");*/
                }
            }

            else {
                
                $this->stdout('Instansi = '.$nama_instansi.' - '.$model->id . "\n");
                $this->stdout('Bagian = '.$bagian.' - '.@$modelBidang->id . "\n");
                $this->stdout('Jabatan = ' .$jabatan. "\n\n");

                $id_jenis_jabatan = 2;

                $jabatan_evjab = new JabatanEvjab();
                $jabatan_evjab->id_jenis_jabatan = $id_jenis_jabatan;
                $jabatan_evjab->id_instansi = $model->id;
                $jabatan_evjab->id_instansi_bidang = @$modelBidang->id;
                $jabatan_evjab->nama = $jabatan;
                $jabatan_evjab->nilai_jabatan = $nilai_jabatan;
                $jabatan_evjab->kelas_jabatan = $kelas_jabatan;
                $jabatan_evjab->nomor = $no;

                $jabatan_evjab->save();
            }
        }
    }
}
