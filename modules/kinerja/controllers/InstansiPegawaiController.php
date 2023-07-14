<?php

namespace app\modules\kinerja\controllers;

use app\components\Helper;
use app\components\Session;
use app\models\Eselon;
use app\models\Instansi;
use app\models\InstansiPegawai;
use app\models\InstansiPegawaiSearch;
use app\models\User;
use app\modules\kinerja\models\IkiForm;
use app\modules\kinerja\models\KegiatanHarian;
use app\modules\kinerja\models\KegiatanHarianSearch;
use app\modules\kinerja\models\KegiatanRhkJenis;
use app\modules\kinerja\models\SkpForm;
use app\modules\tandatangan\models\Berkas;
use app\modules\tandatangan\models\BerkasJenis;
use kartik\mpdf\Pdf;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\httpclient\Client;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use Exception;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use yii\helpers\ArrayHelper;

/**
 * InstansiPegawaiController implements the CRUD actions for InstansiPegawai model.
 */
class InstansiPegawaiController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
            ],
        ];
    }

    /**
     * Lists all InstansiPegawai models.
     * @return mixed
     */

    public function actionIndex()
    {
        $searchModel = new InstansiPegawaiSearch();

        $searchModel->bulan = date('n');

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all InstansiPegawai models.
     * @return mixed
     */

    public function actionIndexV2()
    {
        $searchModel = new InstansiPegawaiSearch();

        $searchModel->bulan = date('n');

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index-v2', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexV3()
    {
        $searchModel = new InstansiPegawaiSearch();
        $searchModel->bulan = date('n');

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index-v3', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexBawahan()
    {
        User::redirectDefaultPassword();

        $searchModel = new InstansiPegawaiSearch();
        $dataProvider = $searchModel->searchBawahan(Yii::$app->request->queryParams);

        if (Yii::$app->request->get('export')) {
            return $this->exportExcel(Yii::$app->request->queryParams);
        }

        return $this->render('index-bawahan', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexBawahanV2()
    {
        User::redirectDefaultPassword();

        $searchModel = new InstansiPegawaiSearch();
        $dataProvider = $searchModel->searchBawahan(Yii::$app->request->queryParams);

        if (Yii::$app->request->get('export')) {
            return $this->exportExcel(Yii::$app->request->queryParams);
        }

        return $this->render('index-bawahan-v2', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexRekap($export = false)
    {
        ini_set('memory_limit', -1);
        $searchModel = new InstansiPegawaiSearch();

        $searchModel->load(Yii::$app->request->queryParams);

        if ($searchModel->bulan == null) {
            $searchModel->bulan = date('n');
        }

        if ($searchModel->tahun == null) {
            $searchModel->tahun = Session::getTahun();
        }

        if($export==true) {
            return $this->exportPdfRekap($searchModel);
        }

        if(Yii::$app->request->get('kirim-dokumen') !== null) {
            if($searchModel->id_instansi == null) {
                Yii::$app->session->setFlash('danger', 'Silahkan pilih unit kerja terlebih dahulu');
                return $this->redirect(Yii::$app->request->referrer);
            }

            return $this->kirimDokumen($searchModel);
        }

        return $this->render('index-rekap', [
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Lists all InstansiPegawai models.
     * @return mixed
     */

    public function actionIndexRekapKegiatanHarianV3()
    {
        $searchModel = new InstansiPegawaiSearch();
        $searchModel->bulan = date('n');

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (Yii::$app->request->get('export-excel')) {
            if($searchModel->id_instansi == null) {
                Yii::$app->session->setFlash('danger', 'Silahkan pilih unit kerja terlebih dahulu');
                return $this->redirect(Yii::$app->request->referrer);
            }

            return $this->exportExcelRekapKegiatanHarian($searchModel);
        }

        return $this->render('index-rekap-kegiatan-harian-v3', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexRekapKegiatanHarianV4()
    {
        $searchModel = new InstansiPegawaiSearch();
        $searchModel->bulan = date('n');

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (Yii::$app->request->get('export-excel')) {
            if($searchModel->id_instansi == null) {
                Yii::$app->session->setFlash('danger', 'Silahkan pilih unit kerja terlebih dahulu');
                return $this->redirect(Yii::$app->request->referrer);
            }

            return $this->exportExcelRekapKegiatanHarian($searchModel);
        }

        return $this->render('index-rekap-kegiatan-harian-v4', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single InstansiPegawai model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($export = null)
    {
        $model = new SkpForm();
        $model->load(Yii::$app->request->queryParams);
        $export = strtolower($export);
        if ($export === 'pdf') {
            return $this->exportPdfSkp($model);
        } elseif ($export === 'excel') {
            return $this->exportExcelSkp($model);
        }
        return $this->render('view', [
            'model' => $model,
        ]);
    }

        /**
     * Displays a single InstansiPegawai model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionViewV2($export = null)
    {
        $model = new SkpForm();
        $model->load(Yii::$app->request->queryParams);
        $export = strtolower($export);
        if ($export === 'pdf') {
            return $this->exportPdfSkpV2($model);
        } elseif ($export === 'excel') {
            return $this->exportExcelSkp($model);
        }
        return $this->render('view-v2', [
            'model' => $model,
        ]);
    }

    public function exportPdfSkp(SkpForm $model)
    {
        $content = $this->renderPartial('_pdf-skp', ['model' => $model]);
        $cssInline = <<<CSS
        div {
            width: 100%;
        }

        table {
            width: 100%;
        }

CSS;

        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => [215.9, 330],
            'defaultFontSize' => '12',
            'marginLeft' => 7,
            'marginRight' => 7,
            'marginTop' => 7,
            'marginBottom' => 7,
            'orientation' => Pdf::ORIENT_LANDSCAPE,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'cssInline' => $cssInline,
            'options' => ['title' => 'Export PDF SKP'],
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }

    public function exportPdfSkpV2(SkpForm $model)
    {
        $content = $this->renderPartial('_pdf-skp-v2', ['model' => $model]);
        $cssInline = <<<CSS
        div {
            width: 100%;
        }

        table {
            width: 100%;
        }

CSS;

        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => [215.9, 330],
            'defaultFontSize' => '12',
            'marginLeft' => 7,
            'marginRight' => 7,
            'marginTop' => 7,
            'marginBottom' => 7,
            'orientation' => Pdf::ORIENT_LANDSCAPE,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'cssInline' => $cssInline,
            'options' => ['title' => 'Export PDF SKP'],
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }

    public function exportPdfRekap(InstansiPegawaiSearch $searchModel, $tandatangan=false, $save=false)
    {
        ini_set('memory_limit', '-1');
        $file = Berkas::findFileBerkas($searchModel, BerkasJenis::REKAP_SKP_DAN_RKB);
        if($file !== null) {
            return $this->redirect($file);
        }

        $content = $this->renderPartial('_pdf-rekap', [
            'searchModel' => $searchModel,
            'tandatangan' => $tandatangan,
        ]);

        $footer = '';
        if($tandatangan == true) {
            $footer = 'Dokumen ini ditandatangani secara elektronik menggunakan Sertifikat Elektronik yang diterbitkan oleh Balai Sertifikasi Elektronik (BSrE), Badan Siber dan Sandi Negara (BSSN)';
        }

        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => [215.9,330],
            'defaultFontSize' => '4',
            'cssInline' => '.bg-gray {
                    color: #000;
                    background-color: #d2d6de !important;
                } td { padding:5px; }',
            'marginLeft'=>7,
            'marginRight'=>7,
            'marginTop'=>7,
            'marginBottom'=>7,
            'orientation' => Pdf::ORIENT_LANDSCAPE,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'options' => ['title' => 'Export PDF Rekap CKHP SKP'],
            'methods' => [
                'SetHTMLFooter' => $footer,
            ],
        ]);

        if($save == true) {
            $path = '../files/';
            $filename = time().'-rekap-skp-rkb.pdf';
            if(file_exists($path.$filename)) {
                $filename = (time()+60).'-rekap-skp-rkb.pdf';
            }
            $filepath = $path.$filename;
            $pdf->Output($content, $filepath, 'F');
            return $filepath;
        }

        return $pdf->render();
    }

    public function exportExcelSkp(SkpForm $model)
    {
        if (User::isPegawai()) {
            $id_pegawai = User::getIdPegawai();
        }
        $pegawai = $model->pegawai;
        $instansiPegawai = $model->getInstansiPegawai();
        $PHPExcel = new \PHPExcel();
        $PHPExcel->setActiveSheetIndex();
        $sheet = $PHPExcel->getActiveSheet();

        $sheet->getDefaultStyle()->getAlignment()->setWrapText(true);
        $sheet->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $setBorderArray = [
            'borders' => [
                'allborders' => [
                    'style' => \PHPExcel_Style_Border::BORDER_THIN
                ]
            ]
        ];
        $setBorderOutline = [
            'borders' => [
                'outline' => [
                    'style' => \PHPExcel_Style_Border::BORDER_THIN
                ]
            ]
        ];

        $sheet->getColumnDimension('A')->setWidth(1);
        $sheet->getColumnDimension('B')->setWidth(3);
        $sheet->getColumnDimension('C')->setWidth(14);
        $sheet->getColumnDimension('D')->setWidth(31);
        $sheet->getColumnDimension('E')->setWidth(4);
        $sheet->getColumnDimension('F')->setWidth(7);
        $sheet->getColumnDimension('G')->setWidth(8);
        $sheet->getColumnDimension('H')->setWidth(6);
        $sheet->getColumnDimension('I')->setWidth(5);
        $sheet->getColumnDimension('J')->setWidth(8);
        $sheet->getColumnDimension('K')->setWidth(8);
        $sheet->getColumnDimension('L')->setWidth(8);
        $sheet->getColumnDimension('M')->setWidth(9);

        $PHPExcel->getActiveSheet()->setCellValue('A1', 'SASARAN KERJA PEGAWAI');
        $PHPExcel->getActiveSheet()->mergeCells('A1:M1');

        $sheet->getStyle('A1:M1')->getFont()->setBold(true);

        $sheet->getStyle('A1:M1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('B4', 'NO');
        $sheet->setCellValue('C4', 'I. PEJABAT PENILAI');
        $sheet->setCellValue('E4', 'NO');
        $sheet->setCellValue('F4', 'II. PEGAWAI NEGERI SIPIL YANG DINILAI');

        $sheet->setCellValue('B5', '1');
        $sheet->setCellValue('C5', 'Nama');
        $sheet->setCellValue('D5', @$instansiPegawai->atasan->nama);
        $sheet->setCellValue('E5', '1');
        $sheet->setCellValue('F5', 'Nama');
        $sheet->setCellValue('I5', @$pegawai->nama);

        $sheet->setCellValue('B6', '2');
        $sheet->setCellValue('C6', 'NIP');
        $sheet->setCellValue('D6', @$instansiPegawai->atasan->nip . ' ');
        $sheet->setCellValue('E6', '2');
        $sheet->setCellValue('F6', 'NIP');
        $sheet->setCellValue('I6', @$pegawai->nip . ' ');

        $sheet->setCellValue('B7', '3');
        $sheet->setCellValue('C7', 'Pangkat/Gol.Ruang');
        $sheet->setCellValue('D7', "");
        $sheet->setCellValue('E7', '3');
        $sheet->setCellValue('F7', 'Pangkat/Gol.Ruang');
        $sheet->setCellValue('I7', "");

        $sheet->setCellValue('B8', '4');
        $sheet->setCellValue('C8', 'Jabatan');
        $sheet->setCellValue('D8', @$instansiPegawai->atasan->nama_jabatan);
        $sheet->setCellValue('E8', '4');
        $sheet->setCellValue('F8', 'Jabatan');
        $sheet->setCellValue('I8', @$instansiPegawai->nama_jabatan);

        $sheet->setCellValue('B9', '5');
        $sheet->setCellValue('C9', 'Unit Kerja');
        $sheet->setCellValue('D9', @$instansiPegawai->atasan->instansi->nama);
        $sheet->setCellValue('E9', '5');
        $sheet->setCellValue('F9', 'Unit Kerja');
        $sheet->setCellValue('I9', @$instansiPegawai->instansi->nama);

        $sheet->getStyle('B4:M4')->getFont()->setBold(true);

        $sheet->getStyle('B4:M4')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B5:B9')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E5:E9')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('B4:L4')->applyFromArray($setBorderArray);

        $sheet->getStyle('B5:B9')->applyFromArray($setBorderOutline);
        $sheet->getStyle('C5:C9')->applyFromArray($setBorderOutline);
        $sheet->getStyle('D5:D9')->applyFromArray($setBorderOutline);
        $sheet->getStyle('E5:E9')->applyFromArray($setBorderOutline);

        $sheet->getStyle('F5:H9')->applyFromArray($setBorderOutline);
        $sheet->getStyle('I5:M9')->applyFromArray($setBorderOutline);

        $PHPExcel->getActiveSheet()->mergeCells('C4:D4');
        $PHPExcel->getActiveSheet()->mergeCells('F4:M4');

        $PHPExcel->getActiveSheet()->mergeCells('F5:H5');
        $PHPExcel->getActiveSheet()->mergeCells('F6:H6');
        $PHPExcel->getActiveSheet()->mergeCells('F7:H7');
        $PHPExcel->getActiveSheet()->mergeCells('F8:H8');
        $PHPExcel->getActiveSheet()->mergeCells('F9:H9');

        $PHPExcel->getActiveSheet()->mergeCells('I5:M5');
        $PHPExcel->getActiveSheet()->mergeCells('I6:M6');
        $PHPExcel->getActiveSheet()->mergeCells('I7:M7');
        $PHPExcel->getActiveSheet()->mergeCells('I8:M8');
        $PHPExcel->getActiveSheet()->mergeCells('I9:M9');


        $sheet->setCellValue('B10', 'NO');
        $sheet->setCellValue('C10', 'III. KEGIATAN TUGAS JABATAN');
        $sheet->setCellValue('E10', 'AK*');
        $sheet->setCellValue('F10', 'TARGET');
        $sheet->setCellValue('F11', 'KUANTITAS / OUTPUT');
        $sheet->setCellValue('H11', 'KUALITAS/ MUTU');
        $sheet->setCellValue('J11', 'WAKTU');
        $sheet->setCellValue('L11', 'BIAYA **');
        $sheet->setCellValue('M10', 'STATUS');

        $sheet->getStyle('B10:M11')->applyFromArray($setBorderArray);

        $PHPExcel->getActiveSheet()->mergeCells('B10:B11');
        $PHPExcel->getActiveSheet()->mergeCells('C10:D11');
        $PHPExcel->getActiveSheet()->mergeCells('E10:E11');
        $PHPExcel->getActiveSheet()->mergeCells('F10:L10');

        $PHPExcel->getActiveSheet()->mergeCells('F11:G11');
        $PHPExcel->getActiveSheet()->mergeCells('H11:I11');
        $PHPExcel->getActiveSheet()->mergeCells('J11:K11');
        $PHPExcel->getActiveSheet()->mergeCells('M10:M11');

        $sheet->getStyle('B10:M11')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B10:M11')->getFont()->setBold(true);

        $total_ak = 0;
        $row = 12;
        $i = 1;
        foreach ($model->getManyKegiatanTahunan()->all() as $kegiatanTahunan) {
            $total_ak += $kegiatanTahunan->target_angka_kredit;

            $sheet->setCellValue('B' . $row, $i);
            $sheet->setCellValue('C' . $row, $kegiatanTahunan->nama);
            $sheet->setCellValue('E' . $row, $kegiatanTahunan->target_angka_kredit);
            $sheet->setCellValue('F' . $row, $kegiatanTahunan->target_kuantitas);
            $sheet->setCellValue('G' . $row, $kegiatanTahunan->satuan_kuantitas);
            $sheet->setCellValue('H' . $row, "100");
            $sheet->setCellValue('J' . $row, $kegiatanTahunan->target_waktu);
            $sheet->setCellValue('K' . $row, "Bulan");
            $sheet->setCellValue('L' . $row, $kegiatanTahunan->target_biaya);
            $sheet->setCellValue('M' . $row, @$kegiatanTahunan->kegiatanStatus->nama);

            $PHPExcel->getActiveSheet()->mergeCells('C' . $row . ':D' . $row);
            $PHPExcel->getActiveSheet()->mergeCells('H' . $row . ':I' . $row);
            $row++;
            $i++;

        }

        $sheet->getStyle('E12:M' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('B12:B' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('B12:M' . $row)->applyFromArray($setBorderArray);

        $sheet->getStyle("A1:M" . $row)->getFont()->setSize(10);

        $sheet->getStyle('C' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('B4:M' . $row)->applyFromArray($setBorderOutline);

        $styleArray = [
            'font' => [
                'color' => ['rgb' => '4D4D4D'],
                'size' => 10,
            ]];

        $sheet->getStyle('B4:M' . $row)->applyFromArray($styleArray);


        $path = 'exports/';
        $filename = time() . '_ExportSkp.xlsx';
        $objWriter = \PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
        $objWriter->save($path . $filename);
        return Yii::$app->getResponse()->redirect($path . $filename);
    }

    public function actionIndexIki()
    {
        User::redirectDefaultPassword();

        $model = new IkiForm();
        $model->load(Yii::$app->request->queryParams);
        return $this->render('index-iki', ['model' => $model]);
    }

    public function actionExportExcelIki($id)
    {
        $model = $this->findModel($id);
        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0);
        $spreadsheet->garbageCollect();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(2);
        $sheet->getColumnDimension('D')->setWidth(10);
        $sheet->getColumnDimension('E')->setWidth(40);
        $sheet->getColumnDimension('F')->setWidth(40);
        $sheet->getColumnDimension('G')->setWidth(40);

        $header = 2;
        $sheet->setCellValue("A$header", 'INDIKATOR KINERJA INDIVIDU');
        $sheet->mergeCells("A$header:G$header");
        $sheet->getStyle("A$header")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 18,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        $headerAawal = $header += 2;

        $sheet->setCellValue("A$header", "1.")
            ->setCellValue("B$header", 'Jabatan')
            ->setCellValue("C$header", ':')
            ->setCellValue("D$header", $model->getNamaJabatan(false));
        $header++;

        $sheet->setCellValue("A$header", "2.")
            ->setCellValue("B$header", 'Tugas')
            ->setCellValue("C$header", ':');
        $chr = 97;
        if ($model->manyInstansiPegawaiTugas !== []) {
            foreach ($model->manyInstansiPegawaiTugas as $key => $tugas) {
                $sheet->setCellValue("D$header", sprintf("%s. %s", chr($chr++), $tugas->nama));
                $header++;
            }
        } else {
            $header++;

        }

        $sheet->setCellValue("A$header", "3.")
            ->setCellValue("B$header", 'Fungsi')
            ->setCellValue("C$header", ':');
        $chr = 97;
        if ($model->manyInstansiPegawaiFungsi !== []) {
            foreach ($model->manyInstansiPegawaiFungsi as $key => $fungsi) {
                $sheet->setCellValue("D$header", sprintf("%s. %s", chr($chr++), $fungsi->nama));
                $header++;
            }
        } else {
            $header++;
        }

        $sheet->getStyle("A$headerAawal:B$header")->getFont()->setBold(true);

        $rowHeader = $header++;
        $sheet->setCellValue("A$rowHeader", 'No')
            ->setCellValue("B$rowHeader", 'Sasaran')
            ->setCellValue("E$rowHeader", 'Indikator Kinerja')
            ->setCellValue("F$rowHeader", 'Penjelasan/Formulasi Perhitungan')
            ->setCellValue("G$rowHeader", 'Sumber Data');
        $sheet->mergeCells("B$rowHeader:D$rowHeader");
        $sheet->getStyle("A$rowHeader:G$rowHeader")->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ]
        ]);
        $row = $rowHeader;
        $i = 0;
        foreach ($model->manyInstansiPegawaiSasaran as $sasaran) {
            $row++;
            $i++;
            $manyIndikator = $sasaran->manyInstansiPegawaiSasaranIndikator;
            $countIndikator = count($manyIndikator);
            $firstIndikator = $manyIndikator !== [] ? array_shift($manyIndikator) : null;
            $sheet->setCellValue("A$row", "$i.")
                ->setCellValue("B$row", $sasaran->nama);
            $sheet->mergeCells("B$row:D$row");
            if ($countIndikator > 1) {
                $countIndikator--;
                $sheet->mergeCells("A$row:A" . ($row + $countIndikator));
                $sheet->mergeCells("B$row:D" . ($row + $countIndikator));
            }
            if ($firstIndikator !== null) {
                $this->renderIndikator($sheet, $row, $firstIndikator);
            }
            foreach ($manyIndikator as $indikator) {
                $row++;
                $this->renderIndikator($sheet, $row, $indikator);
            }
        }

        $sheet->getStyle("A$rowHeader:A$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)
        ->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle("B$rowHeader:B$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)
            ->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle("A$rowHeader:G$row")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ],
            ],
            'alignment' => [
                'wrapText' => true,
            ],
        ]);

        $path = '../files/';
        $filename = time() . '_ExportIki.xlsx';
        $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
        $writer->save($path . $filename);
        return $this->redirect(['/file/get', 'fileName' => $filename]);
    }

    private function renderIndikator(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet, int $row, \app\modules\kinerja\models\InstansiPegawaiSasaranIndikator $indikator)
    {
        $sheet->setCellValue("E$row", $indikator->nama)
            ->setCellValue("F$row", $indikator->penjelasan)
            ->setCellValue("G$row", $indikator->sumber_data);
    }

    public function exportExcelRekapIki(InstansiPegawaiSearch $searchModel, \app\models\InstansiPegawaiQuery $querySearch)
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0);
        $spreadsheet->garbageCollect();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(10);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(13);
        $sheet->getColumnDimension('E')->setWidth(13);
        $sheet->getColumnDimension('F')->setWidth(30);
        $sheet->getColumnDimension('G')->setWidth(13);
        $sheet->getColumnDimension('H')->setWidth(13);

        $header = 2;
        $sheet->setCellValue("A$header", "REKAP IKI");
        $sheet->mergeCells("A$header:H$header");
        $sheet->getStyle("A$header")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 18,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        $header++;
        $sheet->setCellValue("A$header", 'Nama Instansi');
        $sheet->setCellValue("C$header", ': ' . $searchModel->instansi->nama);
        $sheet->mergeCells("A$header:B$header");

        $header++;
        $sheet->setCellValue("A$header", 'Bulan');
        $sheet->setCellValue("C$header", ': ' . Helper::getBulanLengkap($searchModel->bulan));
        $sheet->mergeCells("A$header:B$header");

        $rowHeader = $header += 2;

        $sheet->setCellValue("A$rowHeader", 'No')
            ->setCellValue("B$rowHeader", 'Nama')
            ->setCellValue("D$rowHeader", 'Gol')
            ->setCellValue("E$rowHeader", 'Eselon')
            ->setCellValue("F$rowHeader", 'Jabatan')
            ->setCellValue("G$rowHeader", 'Jenis Jabatan')
            ->setCellValue("H$rowHeader", 'Keterangan');
        $sheet->mergeCells("B$rowHeader:C$rowHeader");
        $sheet->getStyle("A$rowHeader:H$rowHeader")
            ->applyFromArray([
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ]
            ]);
        $i = 0;
        $row = $rowHeader;
        foreach ($querySearch->all() as $instansiPegawai) {
            $i++;
            $row++;
            $sheet->mergeCells("B$row:C$row");
            $sheet->setCellValue("A$row", $i)
                ->setCellValue("B$row", $instansiPegawai->pegawai->nama)
                ->setCellValue("D$row", @$instansiPegawai->pegawai->golongan->golongan)
                ->setCellValue("E$row", @$instansiPegawai->eselon->nama)
                ->setCellValue("F$row", $instansiPegawai->getNamaJabatan(false))
                ->setCellValue("G$row", @$instansiPegawai->getJenisJabatan())
                ->setCellValue("H$row", $instansiPegawai->isMengisiIki() ? 'Sudah' : 'Belum');
        }

        $sheet->getStyle("A$rowHeader:A$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle("B$rowHeader:B$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)
            ->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle("D$rowHeader:E$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle("G$rowHeader:H$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle("A$rowHeader:H$row")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ],
            ],
            'alignment' => [
                'wrapText' => true,
            ],
        ]);

        $path = '../files/';
        $filename = time() . '_ExportRekapIki.xlsx';
        $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
        $writer->save($path . $filename);
        return $this->redirect(['/file/get', 'fileName' => $filename]);
    }

    /**
     * Finds the InstansiPegawai model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return InstansiPegawai the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InstansiPegawai::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionExportPdfIki($id)
    {
        $model = $this->findModel($id);
        $content = $this->renderPartial('export-pdf-iki', ['model' => $model]);
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
            'marginBottom' => 7,
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
            'options' => ['title' => 'Export PDF Presensi'],
            // call mPDF methods on the fly
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }

    public function actionRekapIki($export = false, $exportExcel = false)
    {
        $searchModel = new InstansiPegawaiSearch();
        $querySearch = $searchModel->getQuerySearch(Yii::$app->request->queryParams);
        $querySearch->orderBy(['instansi_pegawai.id_eselon' => SORT_ASC, 'pegawai.id_golongan' => SORT_DESC]);
        $querySearch->with([
            'manyInstansiPegawaiSasaran',
            'manyInstansiPegawaiFungsi',
            'manyInstansiPegawaiSasaran',
        ]);

        if ($export) {
            return $this->exportRekapIki($searchModel, $querySearch);
        }
        if ($exportExcel) {
            return $this->exportExcelRekapIki($searchModel, $querySearch);
        }

        return $this->render('rekap-iki', [
            'searchModel' => $searchModel,
            'querySearch' => $querySearch,
        ]);
    }

    private function exportRekapIki(InstansiPegawaiSearch $searchModel, \app\models\InstansiPegawaiQuery $querySearch)
    {
        $content = $this->renderPartial('export-rekap-iki', ['querySearch' => $querySearch, 'searchModel' => $searchModel]);
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
            'marginBottom' => 7,
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
            'options' => ['title' => 'Export PDF Presensi'],
            // call mPDF methods on the fly
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }

    public function kirimDokumen($searchModel)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(300); // max 5 mnt

        $url = @Yii::$app->params['url_tandatangan'];
        $params = '/api/berkas/save';
        $id_berkas_jenis = Yii::$app->request->get('kirim-dokumen');

        if($url == null) {
            Yii::$app->session->setFlash('warning', 'URL pengiriman belum disetting!');
            return $this->redirect(Yii::$app->request->referrer);
        }

        $file = Berkas::findFileBerkas($searchModel, $id_berkas_jenis);
        if($file != null) {
            Yii::$app->session->setFlash('danger', 'Dokumen sudah pernah dikirim');
            return $this->redirect(Yii::$app->request->referrer);
        }

        $nama = null;
        $berkas_mentah = null;
        $berkas_mentah_tandatangan = null;

        try {
            if($id_berkas_jenis == BerkasJenis::REKAP_SKP_DAN_RKB) {
                $nama = 'Rekap SKP dan RKB';
                $berkas_mentah = $this->exportPdfRekap($searchModel, false, true);
                $berkas_mentah_tandatangan = $this->exportPdfRekap($searchModel, true, true);
            }

            if($berkas_mentah == null OR $berkas_mentah_tandatangan == null) {
                Yii::$app->session->setFlash('warning', 'Jenis dokumen tidak ditemukan');
                return $this->redirect(Yii::$app->request->referrer);
            }

            // $data_verifikasi = InstansiPegawai::getListDataVerifikasiTandatangan();
            $data_verifikasi = [];
            $instansi = Instansi::findOne($searchModel->id_instansi);
            $nip_tandatangan = @$instansi->pegawaiKepala->nip;

            $client = new Client();
            $response = $client->createRequest()
                ->setMethod('POST')
                ->setUrl($url.$params)
                ->addData([
                    'nama' => $nama,
                    'id_berkas_jenis' => $id_berkas_jenis,
                    'id_instansi' => $searchModel->id_instansi,
                    'bulan' => $searchModel->bulan,
                    'tahun' => Session::getTahun(),
                    'nip_tandatangan' => $nip_tandatangan,
                    'data_verifikasi' => $data_verifikasi,
                    'user_create' => Session::getUsername(),
                ])
                ->addFile('berkas_mentah', $berkas_mentah)
                ->addFile('berkas_mentah_tandatangan', $berkas_mentah_tandatangan)
                ->send();

            $responseJson = json_decode($response->content);

            if ($response->statusCode != 200) {
                Yii::$app->session->setFlash('danger', $responseJson->message);
                return $this->redirect(Yii::$app->request->referrer);
            }

            Yii::$app->session->setFlash('success', 'Dokumen berhasil dikirim');
            return $this->redirect(Yii::$app->request->referrer);
        } catch (Exception $e) {
            Yii::$app->session->setFlash('danger', 'Terjadi kesalahan');
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exportExcelRekapKegiatanHarian(InstansiPegawaiSearch $searchModel)
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0);
        $spreadsheet->garbageCollect();
        $spreadsheet->getDefaultStyle()->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $sheet = $spreadsheet->getActiveSheet();

        $setBorderArray = array(
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        );

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(13);
        $sheet->getColumnDimension('H')->setWidth(13);

        $instansi = Instansi::findOne($searchModel->id_instansi);

        $bulan = $searchModel->bulan;
        $date = \DateTime::createFromFormat('Y-n-d', Session::getTahun() . '-' . $bulan . '-01');

        $sheet->setCellValue('A2', 'REKAP CKHP & KINERJA BULANAN');

        $sheet->mergeCells('A2:F2');
        $sheet->getStyle('A2:F2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A4', 'Bulan : ' . Helper::getBulanLengkap($bulan) . ' ' . Session::getTahun());
        $sheet->setCellValue('A5', 'Unit Kerja : ' . $instansi->nama);

        $sheet->setCellValue('A6', 'NO');
        $sheet->setCellValue('B6', 'TANGGAL');
        $sheet->setCellValue('C6', 'NAMA');
        $sheet->setCellValue('D6', 'NIP');
        $sheet->setCellValue('E6', '% POTONGAN CKHP');
        $sheet->setCellValue('F6', '% POTONGAN KINERJA BULANAN (INDIKATOR KINERJA INDIVIDU)');

        $query = $searchModel->getQuerySearch(Yii::$app->request->queryParams);
        $allInstansiPegawai = $query->all();

        $no = 1;
        $row = 7;

        foreach ($allInstansiPegawai as $instansiPegawai) {
            $pegawai = @$instansiPegawai->pegawai;

            if ($pegawai == null) {
                continue;
            }

            $potonganKinerjaBulanan = Helper::rp($pegawai->getPersenPotonganSkpBulanan($bulan, Session::getTahun(), [
                'potonganCkhp' => false,
            ]), 0, 2);

            $rowStart = $row;
            for ($i=1; $i<=$date->format('t'); $i++) {
                $datetime = \DateTime::createFromFormat('Y-n-d', Session::getTahun() . '-' . $bulan . '-' . $i);
                $tanggal = $datetime->format('Y-m-d');

                $sheet->setCellValue("A$row", $no++);
                $sheet->setCellValue("B$row", $tanggal);
                $sheet->setCellValue("C$row", $pegawai->nama);
                $sheet->setCellValueExplicit("D$row", $pegawai->nip, DataType::TYPE_STRING);
                $sheet->setCellValue("E$row", $pegawai->getStringPotonganCkhp([
                    'tanggal' => $tanggal,
                ]) ?? 0);

                $sheet->setCellValue("F$row", $potonganKinerjaBulanan);

                $row++;
            }
            $rowEnd = ($row-1);

            $sheet->mergeCells("F$rowStart:F$rowEnd");
        }
        $row--;

        $spreadsheet->getActiveSheet()->setAutoFilter("B6:F$row");

        $sheet->getStyle('A6:F6')->getAlignment()->setWrapText(true);
        $sheet->getStyle("A6:F$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("C7:C$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle("A6:F$row")->applyFromArray($setBorderArray);

        $path = '../files/';
        $filename = time() . '_ExportRekapCkhp.xlsx';
        $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
        $writer->save($path . $filename);
        return $this->redirect(['/file/get', 'fileName' => $filename]);
    }

    public function actionMatriksPeranHasil($id_pegawai=null, $mode=null)
    {
        $searchModel = new InstansiPegawaiSearch();
        $searchModel->id_instansi;
        $searchModel->load(Yii::$app->request->queryParams);

        if (Session::isInstansi() OR Session::isAdminInstansi()) {
            $searchModel->id_instansi = Session::getIdInstansi();
        }

        $status_kepala = 1;

        $id_eselon = null;

        if ($mode == 'administrator') {
            $status_kepala = 0;
            $id_eselon = Eselon::$eselon_iii;
        }

        if ($mode == 'pengawas') {
            $status_kepala = 0;
            $id_eselon = Eselon::$eselon_iv;
        }

        $query = InstansiPegawai::find();
        $query->joinWith(['jabatan']);
        $query->andWhere(['instansi_pegawai.id_instansi' => $searchModel->id_instansi]);
        $query->andFilterWhere([
            'jabatan.status_kepala' => $status_kepala,
            'jabatan.id_eselon' => $id_eselon,
            'instansi_pegawai.id_pegawai' => $id_pegawai,
        ]);
        $query->filterByBulanTahun();

        $allInstansiPegawai = $query->all();

        if (Yii::$app->request->get('export-pdf')) {
            return $this->exportPdfMatriksPeranHasil([
                'allInstansiPegawai' => $allInstansiPegawai,
            ]);
        }

        if (Yii::$app->request->get('export-excel')) {
            return $this->exportExcelMatriksPeranHasil([
                'allInstansiPegawai' => $allInstansiPegawai,
            ]);
        }

        return $this->render('matriks-peran-hasil', [
            'searchModel' => $searchModel,
            'allInstansiPegawai' => $allInstansiPegawai,
        ]);
    }

    protected function exportPdfMatriksPeranHasil(array $params = [])
    {
        $cssInline = <<<CSS
        table {
            width: 100%;
        }

        .table {
            margin-bottom: 0;
        }

        tr th {
            font-weight: normal;
            text-transform: uppercase;
            background-color: #DEEAF6;
            vertical-align: top;
        }

        tr td {
            vertical-align: top;
        }

        .table-bordered td, .table-bordered th, .table-bordered thead tr th {
            border: 1px solid black;
            padding: 3px 5px 3px 5px;
        }

        .padding-0 td, .padding-0 th {
            padding: 0;
        }
CSS;

        $content = $this->renderPartial('export-pdf-matriks-peran-hasil', [
            'instansiPegawaiKepala' => $params['instansiPegawaiKepala'],
        ]);

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
                //'SetHTMLHeader' => '',
                //'SetHTMLFooter' => '',
            ],
            // call mPDF methods on the fly
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }

    protected $setBorderArray = array(
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
            ],
        ],
    );

    protected function exportExcelMatriksPeranHasil(array $params = [])
    {
        $allInstansiPegawai = @$params['allInstansiPegawai'];

        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0);
        $spreadsheet->garbageCollect();
        $spreadsheet->getDefaultStyle()->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
        $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(40);

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(30);

        $row = 2;
        foreach ($allInstansiPegawai as $instansiPegawai) {
            $sheet->setCellValue("B$row", 'MATRIKS PERAN HASIL');
            $row++;

            $this->renderTableMatriksPeranHasil($spreadsheet, $row, $instansiPegawai);

            $row += 2;
        }

        /*
        $sheet->getStyle("A6:F$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("C7:C$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle("A6:F$row")->applyFromArray($setBorderArray);
        */

        $path = '../files/';
        $filename = time() . '_MatriksPeranHasil.xlsx';
        $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
        $writer->save($path . $filename);
        return $this->redirect(['/file/get', 'fileName' => $filename]);
    }

    protected function renderTableMatriksPeranHasil(Spreadsheet $spreadsheet, &$row, InstansiPegawai $instansiPegawaiInduk)
    {
        $rowAwalTabel = $row;

        $allKegiatanRhkInduk = [];
        
        if ($instansiPegawaiInduk !== null) {
            $allKegiatanRhkInduk = $instansiPegawaiInduk->pegawai->findAllKegiatanRhk([
                'id_kegiatan_rhk_jenis' => KegiatanRhkJenis::UTAMA,
                'id_induk_is_null' => true,
                'id_instansi_pegawai' => $instansiPegawaiInduk->id,
            ]);
        }

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue("B$row", 'PEGAWAI');
        $sheet->setCellValue("C$row", 'JABATAN');
        $sheet->setCellValue("D$row", 'OUTPUT ANTARA/OUTPUT/OUTPUT LAYANAN');

        $sheet->getStyle("B$row:D$row")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('B8CCE4');
        $sheet->getStyle("B$row:D$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("B$row:D$row")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension($row)->setRowHeight(30);
        
        $chr = 68; // D

        if (count($allKegiatanRhkInduk) > 1) {
            $sheet->mergeCells("D$row:" . Helper::chr($chr + count($allKegiatanRhkInduk) - 1) . "$row");
        }

        $row = $row + 1;

        if ($instansiPegawaiInduk !== null) {
            $sheet->setCellValue("B$row", $instansiPegawaiInduk->pegawai->nama);
            $sheet->setCellValue("C$row", $instansiPegawaiInduk->getNamaJabatan());

            foreach ($allKegiatanRhkInduk as $kegiatanRhk) {
                $sheet->setCellValue(Helper::chr($chr) . $row, $kegiatanRhk->nama);
                $chr++;
            }

            $chrColor = $chr;
            if (count($allKegiatanRhkInduk) > 0)  {
                $chrColor = $chrColor - 1;
            }
            $sheet->getStyle("B$row:" . Helper::chr($chrColor) . "$row")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('DCE6F1');

            foreach ($instansiPegawaiInduk->manyInstansiPegawaiBawahan as $instansiPegawaiSub) {
                $row++;

                $sheet->setCellValue("B$row", $instansiPegawaiSub->pegawai->nama);
                $sheet->setCellValue("C$row", trim($instansiPegawaiSub->getNamaJabatan()));

                $chr = 68; // D
                $jumlah = 0;

                foreach ($allKegiatanRhkInduk as $kegiatanRhkInduk) {
                    $rowTemp = $row;

                    $allKegiatanRhkBawahan = $instansiPegawaiSub->pegawai->findAllKegiatanRhk([
                        'id_kegiatan_rhk_atasan' =>  $kegiatanRhkInduk->id,
                        'id_kegiatan_rhk_jenis' => KegiatanRhkJenis::UTAMA,
                        'id_induk_is_null' => true,
                        'id_instansi_pegawai' => $instansiPegawaiSub->id,
                    ]);

                    $jumlahKegiatanRhk = 0;
                    foreach ($allKegiatanRhkBawahan as $kegiatanRhkBawahan) {
                        $jumlahKegiatanRhk++;
                        $sheet->setCellValue(Helper::chr($chr) . $rowTemp++, trim($kegiatanRhkBawahan->nama));
                    }
                    $jumlahKegiatanRhk--;

                    if ($jumlahKegiatanRhk > $jumlah) {
                        $jumlah = $jumlahKegiatanRhk;
                    }

                    $chr++;
                }

                $row += $jumlah;
            }

            $chr--;
        }

        $sheet->getStyle("B$rowAwalTabel:" . Helper::chr($chr) . "$row")->getAlignment()->setWrapText(true);
        $sheet->getStyle("B$rowAwalTabel:" . Helper::chr($chr) . "$row")->applyFromArray($this->setBorderArray);
        //$sheet->getStyle("D4:" . Helper::chr($chr) . "$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_JUSTIFY);
    }
}
