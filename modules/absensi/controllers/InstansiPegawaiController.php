<?php

namespace app\modules\absensi\controllers;

use app\components\Helper;
use app\components\Session;
use app\models\Instansi;
use app\models\InstansiPegawai;
use app\models\InstansiPegawaiSearch;
use app\models\Pegawai;
use app\models\PegawaiAbsensiReport;
use app\models\PegawaiPetaAbsensiForm;
use app\models\PegawaiPetaAbsensiReport;
use app\models\User;
use app\modules\absensi\models\ExportPdf;
use app\modules\tandatangan\models\Berkas;
use app\modules\tandatangan\models\BerkasJenis;
use Exception;
use kartik\mpdf\Pdf;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\httpclient\Client;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

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
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index-pegawai', 'index-matriks-pegawai-wfh'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return User::isAdmin()
                                OR User::isInstansi()
                                OR User::isAdminInstansi()
                                OR User::isOperatorAbsen()
                                OR Session::isPemeriksaAbsensi();
                        },
                    ],
                    [
                        'actions' => ['index-rekap-peta'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return User::isAdmin()
                                OR User::isInstansi()
                                OR User::isAdminInstansi()
                                OR User::isOperatorAbsen()
                                OR Session::isPemeriksaAbsensi()
                                OR Session::isPemeriksaKinerja();
                        },
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all InstansiPegawai models.
     * @return mixed
     */
    public function actionIndexPegawai($exportExcel = false)
    {
        $searchModel = new InstansiPegawaiSearch();

        if(date('j') <= 10) {
            $bulan = date('n');

            if($bulan != 1) {
                $bulan = $bulan-1;
            }

            $searchModel->bulan = $bulan;
        }


        if(User::isInstansi()) {
            $searchModel->id_instansi = User::getIdInstansi();
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (isset($_GET['export-pdf'])) {
            if ($searchModel->id_instansi == null) {
                Yii::$app->session->setFlash('danger', 'Silahkan pilih perangkat daerah terlebih dahulu');
                return $this->redirect(['index-pegawai']);
            }
            if($searchModel->bulan == null) {
                Yii::$app->session->setFlash('danger', 'Silahkan pilih bulan terlebih dahulu');
                return $this->redirect(['index-pegawai']);
            }

            return $this->exportPdf($searchModel);
        }

        if(Yii::$app->request->get('kirim-dokumen') !== null) {
            if($searchModel->id_instansi == null) {
                Yii::$app->session->setFlash('danger', 'Silahkan pilih perangkat daerah terlebih dahulu');
                return $this->redirect(['index-pegawai']);
            }
            if($searchModel->bulan == null) {
                Yii::$app->session->setFlash('danger', 'Silahkan pilih bulan terlebih dahulu');
                return $this->redirect(['index-pegawai']);
            }

            return $this->kirimDokumen($searchModel);
        }

        if ($exportExcel) {
            return $this->exportExcelPegawai($searchModel);
        }

        if (isset($_GET['refresh'])) {
            if ($searchModel->id_instansi == null) {
                Yii::$app->session->setFlash('danger', 'Silahkan pilih perangkat daerah terlebih dahulu');
                return $this->redirect(['index-pegawai']);
            }

            $this->refreshPegawaiRekapAbsensi($searchModel);
            $url = Yii::$app->request->url;
            $url = str_replace('&refresh=1', '', $url);
            Yii::$app->session->setFlash('success', 'Proses refresh berhasil dilakukan');
            return $this->redirect($url);
        }

        return $this->render('index-pegawai', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexRekapPeta()
    {
        $pegawaiPetaAbsensiForm = new PegawaiPetaAbsensiForm();
        $pegawaiPetaAbsensiForm->load(Yii::$app->request->queryParams);
        $pegawaiPetaAbsensiForm->setAttribute();

        if (Session::isInstansi() OR Session::isAdminInstansi()) {
            $pegawaiPetaAbsensiForm->id_instansi = Session::getIdInstansi();
        }

        $allPegawaiPetaAbsensiReport = [];

        $query = InstansiPegawai::find();
        $query->andWhere(['id_instansi' => $pegawaiPetaAbsensiForm->id_instansi]);
        $query->berlaku($pegawaiPetaAbsensiForm->date->format('Y-m-15'));
        $query->groupBy(['id_pegawai']);

        foreach ($query->all() as $instansiPegawai) {
            $pegawaiPetaAbsensiReport = new PegawaiPetaAbsensiReport([
                'id_instansi' => $instansiPegawai->id_instansi,
                'id_pegawai' => $instansiPegawai->id_pegawai,
                'id_peta' => $pegawaiPetaAbsensiForm->id_peta,
                'tahun' => Session::getTahun(),
                'bulan' => $pegawaiPetaAbsensiForm->bulan,
            ]);

            $allPegawaiPetaAbsensiReport[] = $pegawaiPetaAbsensiReport;
        }

        if (Yii::$app->request->get('export-pdf')) {
            return $this->exportPdfRekapPeta([
                'pegawaiPetaAbsensiForm' => $pegawaiPetaAbsensiForm,
                'allPegawaiPetaAbsensiReport' => $allPegawaiPetaAbsensiReport,
            ]);
        }

        if (Yii::$app->request->get('export-excel')) {
            return $this->exportExcelRekapPetaPerInstansi([
                'pegawaiPetaAbsensiForm' => $pegawaiPetaAbsensiForm,
            ]);
        }

        return $this->render('index-rekap-peta', [
            'pegawaiPetaAbsensiForm' => $pegawaiPetaAbsensiForm,
            'allPegawaiPetaAbsensiReport' => $allPegawaiPetaAbsensiReport,
        ]);
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
            Yii::$app->session->setFlash('danger', 'Dokumen pada bulan '.Helper::getBulanLengkap($searchModel->bulan).' sudah pernah dikirim');
            return $this->redirect(Yii::$app->request->referrer);
        }

        $nama = null;
        $berkas_mentah = null;
        $berkas_mentah_tandatangan = null;

        try {
            if($id_berkas_jenis == BerkasJenis::REKAP_ABSENSI) {
                $nama = 'Rekap Absensi';
                $berkas_mentah = $this->exportPdf($searchModel, false, true);
                $berkas_mentah_tandatangan = $this->exportPdf($searchModel, true, true);
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

    public function refreshPegawaiRekapAbsensi(InstansiPegawaiSearch $searchModel)
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '300');

        $query = $searchModel->getQuerySearch(Yii::$app->request->queryParams);
        $query->with(['pegawai']);

        foreach($query->all() as $instansiPegawai) {
            $instansiPegawai->pegawai->updatePegawaiRekapAbsensi($searchModel->bulan);
        }

        /* $total = $query->count();
        $selesai = 0;

        foreach($query->all() as $instansiPegawai) {
            $waktu_refresh = $instansiPegawai->waktu_refresh;

            if ($waktu_refresh != null) {
                $datetime = \DateTime::createFromFormat('Y-m-d H:i:s', $waktu_refresh);
                $datetime->modify('+10 minutes');

                if ($datetime->format('Y-m-d H:i:s') >= date('Y-m-d H:i:s')) {
                    $selesai++;
                    continue;
                }
            }

            try {
                $instansiPegawai->pegawai->updatePegawaiRekapAbsensi($searchModel->bulan);
                $instansiPegawai->updateAttributes(['waktu_refresh' => date('Y-m-d H:i:s')]);
                $selesai++;
            } catch (\Error $e) {
                Yii::$app->session->setFlash('warning', "Proses refresh $selesai dari $total data berhasil dilakukan, silahkan refresh lagi untuk melanjutkan");
                return false;
            }
        }

        Yii::$app->session->setFlash('success', 'Proses refresh berhasil dilakukan'); */
    }

    protected $_namaFile;
    public function setNamaFile($tandatangan=false, $jenis='rekap-absensi')
    {
        if ($tandatangan == false) {
            return time().'-rekap-absensi.pdf';
        }

        if ($this->_namaFile !== null) {
            return $this->_namaFile;
        }

        $this->_namaFile = time().'-rekap-absensi.pdf';
        return $this->_namaFile;
    }

    public function actionSetFileName()
    {
        $path = '../files/';
        $fileName = time().'-rekap-absensi.pdf';
        if(file_exists($path.$fileName)) {
            $fileName = (time()+60).'-rekap-absensi.pdf';
        }
        return $fileName;
    }

    public function exportPdf(InstansiPegawaiSearch $searchModel, $tandatangan=false, $save=false)
    {
        $file = Berkas::findFileBerkas($searchModel, BerkasJenis::REKAP_ABSENSI);
        if($file !== null) {
            return $this->redirect($file);
        }

        $modelExportPdf = ExportPdf::find()
            ->andWhere(['id_instansi' => $searchModel->id_instansi])
            ->andWhere(['bulan' => $searchModel->bulan])
            ->andWhere(['tahun' => User::getTahun()])
            ->one();

        // Cek model export pdf
        if ($modelExportPdf == null) {
            $modelExportPdf = new ExportPdf();
            $modelExportPdf->id_instansi = $searchModel->id_instansi;
            $modelExportPdf->bulan = $searchModel->bulan;
            $modelExportPdf->tahun = User::getTahun();
            $modelExportPdf->hash = Yii::$app->getSecurity()->generateRandomString(7);
            $modelExportPdf->save(false);
        }

        $query = $searchModel->getQuerySearch(Yii::$app->request->queryParams);
        /* $query->andWhere([
            'status_plt' => 0
        ]); */
        $query->joinWith(['pegawai']);
        $query->with(['pegawai']);
        $query->orderBy(['jabatan.id_eselon' => SORT_ASC]);
        $query->groupBy(['pegawai.nip']);

        $content = $this->renderPartial('export-pdf-index-statis', [
            'query' => $query,
            'searchModel' => $searchModel,
            'modelExportPdf' => $modelExportPdf,
            'tandatangan' => $tandatangan,
        ]);

        $footer = '';
        if($tandatangan == true) {
            $footer = 'Dokumen ini ditandatangani secara elektronik menggunakan Sertifikat Elektronik yang diterbitkan oleh Balai Sertifikasi Elektronik (BSrE), Badan Siber dan Sandi Negara (BSSN)';
        }

        $path = '../files/';
        $fileName = $this->actionSetFileName();
        $filepath = $path.$fileName;

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
            'options' => ['title' => 'Export PDF Presensi'],
            // call mPDF methods on the fly
            'methods' => [
                'SetHTMLHeader' => $this->renderPartial('_barcode', [
                    'modelExportPdf' => $modelExportPdf,
                    'fileName' => $fileName,
                    'tandatangan' => $tandatangan,
                ]),
                'SetHTMLFooter' => $footer,
            ],
        ]);

        if($save == true) {
            $pdf->Output($content, $filepath, 'F');
            return $filepath;
        }

        // return the pdf output as per the destination setting
        return $pdf->render();
    }

    public function exportExcelPegawai(InstansiPegawaiSearch $instansiPegawaiSearch)
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0);
        $spreadsheet->garbageCollect();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(40);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(80);
        $sheet->getColumnDimension('E')->setWidth(65);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(15);

        $sheet->setCellValue("A1", "Daftar Pegawai Bulan " . Helper::getBulanLengkap($instansiPegawaiSearch->bulan) . " Tahun " . User::getTahun());
        $sheet->getStyle("A1")->getFont()->setBold(true);

        $headerAwal = $header = 3;
        $sheet->setCellValue("A$header", 'NO')
            ->setCellValue("B$header","NAMA PEGAWAI")
            ->setCellValue("C$header","NIP")
            ->setCellValue("D$header", "Instansi")
            ->setCellValue("E$header", "Jabatan")
            ->setCellValue("F$header", "Golongan")
            ->setCellValue("G$header", "Status Aktif");
        $sheet->getStyle("A$headerAwal:G$header")
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
        $querySearch = $instansiPegawaiSearch->getQuerySearch(Yii::$app->request->queryParams);
        $querySearch->with(['instansi', 'jabatan']);
        foreach ($querySearch->all() as $instansiPegawai) {
            $row++;
            $sheet->setCellValue("A$row", $i++)
                ->setCellValue("B$row", @$instansiPegawai->pegawai->nama)
                ->setCellValue("C$row", @$instansiPegawai->pegawai->nipFormat)
                ->setCellValue("D$row", @$instansiPegawai->instansi->nama)
                ->setCellValue("E$row", @$instansiPegawai->getNamaJabatan(false))
                ->setCellValue("F$row", @$instansiPegawai->pegawai->namaPegawaiGolonganBerlaku)
                ->setCellValue("G$row", @$instansiPegawai->pegawai->status_hapus === 1 ? 'N' : 'Y');
        }

        $sheet->getStyle("A$header:A$row")
            ->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ]
            ]);
        $sheet->getStyle("C$header:C$row")
            ->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ]
            ]);
        $sheet->getStyle("F$header:G$row")
            ->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ]
            ]);
        $sheet->getStyle("A$header:G$row")
            ->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN
                    ]
                ],
            ]);

        $path = '../files/';
        $filename = time().'_ExportPegawai.xlsx';
        $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
        $writer->save($path . $filename);
        return $this->redirect(['/file/get', 'fileName' => $filename]);
    }

    public function actionIndexMatriksPegawaiWfh($editable=true)
    {
        $searchModel = new InstansiPegawaiSearch();
        $searchModel->search(Yii::$app->request->queryParams);

        if (Session::isInstansi() OR Session::isAdminInstansi()) {
            $searchModel->id_instansi = Session::getIdInstansi();
        }

        if($searchModel->bulan == null) {
            $searchModel->bulan = date('n');
        }

        $tahun = Session::getTahun();
        $bulan = $searchModel->bulan;

        if($bulan == null) {
            $bulan = date('n');
        }

        $datetime = \DateTime::createFromFormat('Y-n', $tahun.'-'.$bulan);

        $allInstansiPegawai = [];
        $pagination = new Pagination(['totalCount' => 0]);

        if($searchModel->id_instansi != null) {

            $tanggal = $datetime->format('Y-m-15');

            $query = InstansiPegawai::find();
            $query->with(['pegawai']);
            $query->joinWith(['pegawai']);
            $query->andWhere(['instansi_pegawai.id_instansi' => $searchModel->id_instansi]);
            $query->andWhere('tanggal_mulai <= :tanggal and tanggal_selesai >= :tanggal', [
                ':tanggal' => $tanggal
            ]);

            $query->andFilterWhere(['like','pegawai.nama', $searchModel->nama_pegawai]);

            $countQuery = clone $query;
            $pagination = new Pagination([
                'totalCount' => $countQuery->count(),
                'pageSize' => 10
            ]);

            $allInstansiPegawai = $query->offset($pagination->offset)
                ->limit($pagination->limit)
                ->all();
        }

        return $this->render('index-matriks-pegawai-wfh',[
            'searchModel' => $searchModel,
            'allInstansiPegawai' => $allInstansiPegawai,
            'pagination' => $pagination,
            'datetime' => $datetime,
            'editable' => $editable
        ]);
    }
    
    public function exportPdfRekapPeta(array $params = [])
    {
        $pegawaiPetaAbsensiForm = $params['pegawaiPetaAbsensiForm'];
        $allPegawaiPetaAbsensiReport = $params['allPegawaiPetaAbsensiReport'];

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

        .table tr th, .table tr td {
            padding: 7px;
        }
CSS;

        $content = $this->renderPartial('export-pdf-rekap-peta', [
            'pegawaiPetaAbsensiForm' => $pegawaiPetaAbsensiForm,
            'allPegawaiPetaAbsensiReport' => $allPegawaiPetaAbsensiReport,
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
            'options' => ['title' => 'Export PDF Rekap Absensi'],
            'cssInline' => $cssInline,
            // call mPDF methods on the fly

        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }

    public function exportExcelRekapPetaPerInstansi(array $params = [])
    {
        $pegawaiPetaAbsensiForm = $params['pegawaiPetaAbsensiForm'];

        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0);

        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getDefaultStyle()->getAlignment()->setWrapText(true);
        $spreadsheet->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $setBorderArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ],
        ];

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(10);
        $sheet->getColumnDimension('C')->setWidth(50);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);

        $sheet->setCellValue('B2', 'LOKASI : ');
        $sheet->setCellValue('B3', 'WAKTU : ');

        $sheet->setCellValue('C2', @$pegawaiPetaAbsensiForm->peta->nama);
        $sheet->setCellValue('C3', Helper::getTanggal($pegawaiPetaAbsensiForm->tanggal));

        $sheet->setCellValue('B5', 'NO');
        $sheet->setCellValue('C5', 'PD / UNIT KERJA');
        $sheet->setCellValue('D5', 'JUMLAH PEGAWAI');
        $sheet->setCellValue('E5', 'HADIR');
        $sheet->setCellValue('F5', 'TIDAK HADIR');

        $id_instansi = null;

        if (Session::isAdmin() == false AND $pegawaiPetaAbsensiForm->id_instansi != null) {
            $id_instansi = $pegawaiPetaAbsensiForm->id_instansi;
        }

        $datetime = \DateTime::createFromFormat('Y-m-d', $pegawaiPetaAbsensiForm->tanggal);
        $tanggal = $datetime->format('Y-m-15');

        $query = Instansi::find();
        $query->andFilterWhere([
            'status_aktif' => 1,
            'id' => $id_instansi,
        ]);

        $allInstansi = $query->all();

        $row = 6;
        $i = 1;

        $totalJumlahPegawai = 0;
        $totalJumlahPegawaiHadir = 0;
        foreach ($allInstansi as $instansi) {

            $query = InstansiPegawai::find();
            $query->joinWith(['pegawai']);
            $query->andWhere(['instansi_pegawai.id_instansi' => $instansi->id]);
            $query->berlaku($tanggal);
            $query->groupBy(['instansi_pegawai.id_pegawai']);

            $jumlahPegawai = 0;
            $jumlahPegawaiHadir = 0;
    
            foreach ($query->all() as $instansiPegawai) {
                $pegawaiPetaAbsensiReport = new PegawaiPetaAbsensiReport([
                    'id_instansi' => $instansiPegawai->id_instansi,
                    'id_pegawai' => $instansiPegawai->id_pegawai,
                    'id_peta' => $pegawaiPetaAbsensiForm->id_peta,
                    'tahun' => $datetime->format('Y'),
                    'bulan' => $datetime->format('n'),
                ]);

                if ($pegawaiPetaAbsensiReport->getCountCheckinout(['tanggal' => $pegawaiPetaAbsensiForm->tanggal]) > 0) {
                    $jumlahPegawaiHadir++;
                }

                $jumlahPegawai++;
            }

            $sheet->setCellValue("B$row", $i++);
            $sheet->setCellValue("C$row", $instansi->nama);
            $sheet->setCellValue("D$row", $jumlahPegawai);
            $sheet->setCellValue("E$row", $jumlahPegawaiHadir);
            $sheet->setCellValue("F$row", $jumlahPegawai - $jumlahPegawaiHadir);

            $totalJumlahPegawai += $jumlahPegawai;
            $totalJumlahPegawaiHadir += $jumlahPegawaiHadir;

            $row++;
        }

        $sheet->setCellValue("B$row", 'JUMLAH');
        $sheet->setCellValue("D$row", $totalJumlahPegawai);
        $sheet->setCellValue("E$row", $totalJumlahPegawaiHadir);
        $sheet->setCellValue("F$row", $totalJumlahPegawai - $totalJumlahPegawaiHadir);

        $sheet->mergeCells("B$row:C$row");

        $sheet->getStyle('B5:F5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("B6:B$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("D6:F$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle("B5:F$row")->applyFromArray($setBorderArray);

        $path = Yii::getAlias('@app/files/');
        $filename = time() . '_DataRekap.xlsx';

        $path = '../files/';
        $filename = time() . '_ExportRekapAbsensiPeta.xlsx';
        $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
        $writer->save($path . $filename);
        return $this->redirect(['/file/get', 'fileName' => $filename]);
    }
}
