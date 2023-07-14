<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\Jabatan;
use app\modules\absensi\models\ExportPdf;
use app\modules\absensi\models\PegawaiShiftKerja;
use app\modules\tandatangan\models\Berkas;
use app\modules\tandatangan\models\BerkasJenis;
use kartik\mpdf\Pdf;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Yii;
use app\components\Helper;
use app\models\InstansiPegawai;
use app\models\InstansiPegawaiSearch;
use app\models\Pegawai;
use yii\base\BaseObject;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class InstansiPegawaiController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex()
    {
        $this->stdout("Hello World".PHP_EOL);
        $date = \DateTime::createFromFormat('Y-m-d','2019-01-28');
        $date->modify('+1 month');
        $this->stdout($date->format('Y-m-d'));
    }

    public function actionUpdateTanggalSelesai()
    {
        $query = InstansiPegawai::find();


        $done = 0;
        $total = $query->count();

        Console::startProgress($done,$total);

        foreach($query->all() as $instansiPegawai) {
            $instansiPegawai->updateTanggalSelesai();
            Console::updateProgress($done++,$total);
        }

        Console::endProgress();
    }

    public function actionUpdateTanggalMulai()
    {
        $query = InstansiPegawai::find();

        $done = 0;
        $total = $query->count();

        Console::startProgress($done,$total);

        foreach($query->all() as $instansiPegawai) {
            $instansiPegawai->updateTanggalMulai();
            Console::updateProgress($done++,$total);
        }

        Console::endProgress();
    }

    public function actionGenerateSkp($tahun)
    {
        $query = InstansiPegawai::find();
        $query->filterByTahun($tahun);

        $query->orderBy(['tanggal_mulai'=>SORT_ASC]);

        foreach($query->all() as $data) {
            $data->findOrCreateInstansiPegawaiSkp(['tahun'=>$tahun]);
            $this->stdout("ID Pegawai : $data->id_pegawai; Berlaku : $data->tanggal_berlaku; Selesai: $data->tanggal_selesai".PHP_EOL);
        }
    }

    public function actionRefreshSkp($tahun)
    {

    }

    public function actionJumlahIdJabatanNull()
    {
        $query = InstansiPegawai::find();
        $query->andWhere('id_jabatan IS NULL');
        $count = $query->count();

        $this->stdout("Jumlah id_jabatan NULL : $count");
    }

    public function actionExportExcelKeseluruhan()
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0);
        $spreadsheet->garbageCollect();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(40);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(35);
        $sheet->getColumnDimension('F')->setWidth(25);
        $sheet->getColumnDimension('G')->setWidth(40);
        $sheet->getColumnDimension('H')->setWidth(18);
        $sheet->getColumnDimension('I')->setWidth(20);
        $sheet->getColumnDimension('J')->setWidth(75);
        $sheet->getColumnDimension('K')->setWidth(50);
        $sheet->getColumnDimension('L')->setWidth(20);
        $sheet->getColumnDimension('M')->setWidth(20);
        $sheet->getColumnDimension('N')->setWidth(15);
        $sheet->getColumnDimension('O')->setWidth(15);
        $sheet->getColumnDimension('P')->setWidth(25);
        $sheet->getColumnDimension('Q')->setWidth(25);
        $sheet->getColumnDimension('R')->setWidth(25);
        $sheet->getColumnDimension('S')->setWidth(25);

        $instansiPegawaiSearch = new InstansiPegawaiSearch();
        $instansiPegawaiSearch->tahun = date('Y');
        $instansiPegawaiSearch->bulan = date('m');

        $querySearch = $instansiPegawaiSearch->getQuerySearch();
        /*$querySearch->limit(20);*/
        $querySearch->with(['pegawai.golongan', 'instansi', 'jabatan']);

        $sheet->setCellValue("A1", "Daftar Pegawai Bulan " . Helper::getBulanLengkap($instansiPegawaiSearch->bulan) . " Tahun " . $instansiPegawaiSearch->tahun);
        $sheet->getStyle("A1")->getFont()->setBold(true);

        $sheet->mergeCells('A1:E1');

        $headerAwal = $header = 3;
        $sheet
            ->setCellValue("A$header", 'NO')
            ->setCellValue("B$header", "NAMA PEGAWAI")
            ->setCellValue("C$header", "NIP")
            ->setCellValue("D$header", "JENIS KELAMIN")
            ->setCellValue("E$header", "TEMPAT LAHIR")
            ->setCellValue("F$header", "TANGGAL LAHIR")
            ->setCellValue("G$header", "ALAMAT")
            ->setCellValue("H$header", "TELEPON")
            ->setCellValue("I$header", "EMAIL")
            ->setCellValue("J$header", "UNIT KERJA")
            ->setCellValue("K$header", "JABATAN")
            ->setCellValue("L$header", "GOLONGAN")
            ->setCellValue("M$header", "JENIS JABATAN")
            ->setCellValue("N$header", "KELAS JABATAN")
            ->setCellValue("O$header", "NILAI JABATAN")
            ->setCellValue("P$header", "TANGGAL TMT JABATAN")
            ->setCellValue("Q$header", "TANGGAL MULAI EFEKTIF")
            ->setCellValue("R$header", "SHIFT KERJA")
            ->setCellValue("S$header", "STATUS PEGAWAI");

        $sheet->getStyle("A$headerAwal:S$header")
            ->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN
                    ]
                ],
                'font' => [
                    'bold' => true
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);
        $row = $header;
        $i = 1;

        $queryPegawai = $querySearch->all();
        $done = count($queryPegawai);
        $prog = 0;

        Console::startProgress($prog, $done, 'Mengupdate hasil abk');

        foreach ($queryPegawai as $instansiPegawai) {
            $row++;
            $sheet->setCellValue("A$row", $i)
                ->setCellValue("B$row", @$instansiPegawai->pegawai->nama)
                ->setCellValue("C$row", @$instansiPegawai->pegawai->nipFormat)
                ->setCellValue("D$row", @$instansiPegawai->pegawai->gender)
                ->setCellValue("E$row", @$instansiPegawai->pegawai->tempat_lahir)
                ->setCellValue("F$row", Helper::getTanggal(@$instansiPegawai->pegawai->tanggal_lahir))
                ->setCellValue("G$row", @$instansiPegawai->pegawai->alamat)
                ->setCellValue("H$row", @$instansiPegawai->pegawai->telepon)
                ->setCellValue("I$row", " ".@$instansiPegawai->pegawai->email)
                ->setCellValue("J$row", @$instansiPegawai->instansi->nama)
                ->setCellValue("K$row", @$instansiPegawai->getNamaJabatan(false))
                ->setCellValue("L$row", @$instansiPegawai->pegawai->pegawaiGolongan->golongan->golongan)
                ->setCellValue("M$row", @$instansiPegawai->jabatan->jenisJabatan)
                ->setCellValue("N$row", @$instansiPegawai->jabatan->kelas_jabatan)
                ->setCellValue("O$row", @$instansiPegawai->jabatan->nilai_jabatan)
                ->setCellValue("P$row", Helper::getTanggal(@$instansiPegawai->tanggal_berlaku))
                ->setCellValue("Q$row", Helper::getTanggal(@$instansiPegawai->tanggal_mulai))
                ->setCellValue("R$row", @$instansiPegawai->pegawai->getNamaShiftKerja())
                ->setCellValue("S$row", @$instansiPegawai->getTextStatusPegawai());

                $i++;

                $this->stdout(@$instansiPegawai->instansi->nama, Console::FG_GREEN, Console::BOLD);
                Console::updateProgress(++$prog, $done);
        }


        $sheet->getStyle("A".$header.":A".$row)
            ->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ]
            ]);
        $sheet->getStyle("C".$header.":C".$row)
            ->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ]
            ]);
        $sheet->getStyle("F".$header.":S".$row)
            ->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ]
            ]);
        $sheet->getStyle("A".$header.":S".$row)
            ->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN
                    ]
                ],
            ]);

        $sheet->getStyle("A1:S$row")->getAlignment()->setWrapText(true);

        Console::endProgress();
        echo 'done';

        $path = 'files/keseluruhan/';
        $filename = "Daftar Pegawai Bulan " . Helper::getBulanLengkap($instansiPegawaiSearch->bulan) . " Tahun " . $instansiPegawaiSearch->tahun.'-Keseluruhan-.xlsx';
        $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
        $writer->save($path . $filename);
    }

    public function actionDinasNonAktif()
    {
        $query = InstansiPegawai::query([
            'id_instansi'=>164
        ]);

        foreach($query->all() as $data) {
            if($data->tanggal_selesai == '9999-12-31') {
                $data->updateAttributes([
                    'tanggal_selesai'=>'2019-12-31'
                ]);
            }
        }
    }

    public function actionUpdateField()
    {
        $query = InstansiPegawai::find();
        $query->andWhere(['status_update' => 0]);

        $done = 0;
        $total = $query->count();

        Console::startProgress($done,$total);
        foreach ($query->all() as $data) {

            $data->updateAttributes([
                'nama_jabatan' => $data->getNamaJabatan(),
                'nama_jabatan_atasan' => @$data->jabatanAtasan->nama,
                'nama_instansi' => @$data->instansi->nama,
                'status_update' => 1,
            ]);

            $done++;
            Console::updateProgress($done,$total);
        }
        Console::endProgress();
    }

    public function actionExportPdfPerhitungan($tahun, $bulan, $id_instansi)
    {
        $searchModel = new InstansiPegawaiSearch();
        $searchModel->tahun = $tahun;
        $searchModel->bulan = $bulan;
        $searchModel->id_instansi = $id_instansi;

        $this->exportPdfPerhitungan($searchModel, false, true);
    }

    public function exportPdfPerhitungan($searchModel, $tandatangan=false, $save=false)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(600);

        $file = Berkas::findFileBerkas($searchModel, BerkasJenis::PERHITUNGAN_TPP);
        if($file !== null) {
            return $this->redirect($file);
        }

        $exportPdf = ExportPdf::find()
            ->andWhere(['id_instansi' => $searchModel->id_instansi])
            ->andWhere(['bulan' => $searchModel->bulan])
            ->andWhere(['tahun' => $searchModel->tahun])
            ->one();

        $cssInline = <<<CSS
        table {
            border-spacing: 0;
            padding: 7px;
            font-size: 14px;
            width: 100%;
            border-collapse: collapse;
        }
        th {
            vertical-align: middle;
            text-align: center;
        }
CSS;

        // Cek model export pdf
        if ($exportPdf == null) {
            $exportPdf = new ExportPdf();
            $exportPdf->id_instansi = $searchModel->id_instansi;
            $exportPdf->bulan = $searchModel->bulan;
            $exportPdf->tahun = $searchModel->tahun;
            $exportPdf->hash = Yii::$app->getSecurity()->generateRandomString(7);
            $exportPdf->save(false);
        }

        if (php_sapi_name() == 'cli') {
            $query = $searchModel->getQuerySearch();
        } else {
            $query = $searchModel->getQuerySearch(Yii::$app->request->queryParams);
        }

        $query->andWhere([
            'status_plt' => 0
        ]);

        $query->joinWith(['pegawai','jabatan']);
        $query->with(['pegawai']);
        $query->orderBy(['jabatan.id_eselon' => SORT_ASC]);
        // $query->limit(100);

        if ($exportPdf->tahun >= 2021){
            $content = Yii::$app->controller->renderPartial('command/export-pdf-perhitungan-baru', [
                'query' => $query,
                'searchModel' => $searchModel,
                'modelExportPdf' => $exportPdf,
                'tandatangan' => $tandatangan,
            ]);
        } else {
            $content = Yii::$app->controller->renderPartial('command/export-pdf-perhitungan', [
                'query' => $query,
                'searchModel' => $searchModel,
                'modelExportPdf' => $exportPdf,
                'tandatangan' => $tandatangan
            ]);
        }

        $footer = '';
        if($tandatangan == true) {
            $footer = 'Dokumen ini ditandatangani secara elektronik menggunakan Sertifikat Elektronik yang diterbitkan oleh Balai Sertifikasi Elektronik (BSrE), Badan Siber dan Sandi Negara (BSSN)';
        }

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8,
            // A4 paper format
            //'format' => Pdf::FORMAT_F4,
            'format' => [215.9, 330],
            'defaultFontSize' => '5',
            // portrait orientation
            'marginLeft' => 7,
            'marginRight' => 7,
            'marginTop' => 7,
            'marginBottom' => 15,
            'orientation' => Pdf::ORIENT_LANDSCAPE,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            //'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            //'cssInline' => '.kv-heading-1{font-size:18px}',
            // set mPDF properties on the fly
            'options' => ['title' => 'Export PDF Perhitungan'],
            'cssInline' => $cssInline,
            'methods' => [
                'SetHTMLHeader' => $this->renderPartial('_barcode', ['modelExportPdf' => $exportPdf]),
                'SetHTMLFooter' => $footer,
            ],
            // call mPDF methods on the fly

        ]);

        if($save == true) {
            $path = '../files/';
            $filename = time().'-perhitungan-tpp.pdf';
            if(file_exists($path.$filename)) {
                $filename = (time()+60).'-perhitungan-tpp.pdf';
            }
            $filepath = $path.$filename;
            $pdf->Output($content, $filepath, 'F');
            return $filepath;
        }

        // return the pdf output as per the destination setting
        return $pdf->render();
    }


    public function actionGenerateShiftKerjaPegawai()
    {
        $model = new InstansiPegawai();
        $model->createShiftKerjaPegawai();
    }
}
