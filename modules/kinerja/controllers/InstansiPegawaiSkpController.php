<?php

namespace app\modules\kinerja\controllers;

use app\components\Session;
use app\models\Pegawai;
use app\models\User;
use app\models\InstansiPegawai;
use app\modules\kinerja\models\KegiatanRhkJenis;
use app\modules\kinerja\models\KegiatanTahunan;
use app\modules\kinerja\models\SkpForm;
use app\modules\kinerja\models\SkpPerilakuJenis;
use kartik\mpdf\Pdf;
use Yii;
use app\modules\kinerja\models\InstansiPegawaiSkp;
use app\modules\kinerja\models\InstansiPegawaiSkpSearch;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use function time;

/**
 * InstansiPegawaiSkpController implements the CRUD actions for InstansiPegawaiSkp model.
 */
class InstansiPegawaiSkpController extends Controller
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
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return User::isAdmin();
                        },
                    ],
                    [
                        'actions' => [
                            'index', 'index-v2', 'view','refresh', 'export-pdf-skp', 'export-excel-skp',
                            'view-baru', 'view-v2', 'export-pdf-skp-v2', 'export-pdf-form-ii-jf',
                            'index-v3', 'view-v3', 'export-pdf-skp-v3', 'index-lampiran', 'get-list',
                        ],
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all InstansiPegawaiSkp models.
     * @return mixed
     */
    public function actionIndex($debug=false)
    {
        User::redirectDefaultPassword();

        $searchModel = new InstansiPegawaiSkpSearch();

        if(User::isPegawai()) {
            $searchModel->scenario = InstansiPegawaiSkpSearch::SCENARIO_PEGAWAI;
        }

        if($searchModel->tahun === null) {
            $searchModel->tahun = Session::getTahun();
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if(Yii::$app->request->get('export')) {
            return $this->exportExcel(Yii::$app->request->queryParams);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'debug'=>$debug
        ]);
    }

    public function actionIndexV2($debug=false)
    {
        User::redirectDefaultPassword();

        $searchModel = new InstansiPegawaiSkpSearch();

        if(User::isPegawai()) {
            $searchModel->scenario = InstansiPegawaiSkpSearch::SCENARIO_PEGAWAI;
        }

        if($searchModel->tahun === null) {
            $searchModel->tahun = Session::getTahun();
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if(Yii::$app->request->get('export')) {
            return $this->exportExcel(Yii::$app->request->queryParams);
        }

        return $this->render('index-v2', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'debug'=>$debug
        ]);
    }

    public function actionIndexV3($mode='pegawai', $debug=false)
    {
        User::redirectDefaultPassword();

        $searchModel = new InstansiPegawaiSkpSearch();

        if (User::isPegawai()) {
            $searchModel->scenario = InstansiPegawaiSkpSearch::SCENARIO_PEGAWAI;
        }

        if (User::isPegawai() AND $mode == 'atasan') {
            $searchModel->scenario = InstansiPegawaiSkpSearch::SCENARIO_ATASAN;
        }

        if($searchModel->tahun === null) {
            $searchModel->tahun = Session::getTahun();
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if(Yii::$app->request->get('export')) {
            return $this->exportExcel(Yii::$app->request->queryParams);
        }

        return $this->render('index-v3', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'debug' => $debug,
        ]);
    }

    /**
     * Lists all InstansiPegawaiSkp models.
     * @return mixed
     */
    /*
    public function actionIndexPegawai($debug=false)
    {
        $searchModel = new InstansiPegawaiSkpSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if(Yii::$app->request->get('export')) {
            return $this->exportExcel(Yii::$app->request->queryParams);
        }

        return $this->render('index-pegawai', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'debug'=>$debug
        ]);
    }
    */

    /**
     * Displays a single InstansiPegawaiSkp model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id,$debug=false)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'debug'=>$debug
        ]);
    }

        /**
     * Displays a single InstansiPegawaiSkp model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionViewV2($id,$debug=false)
    {
        return $this->render('view-v2', [
            'model' => $this->findModel($id),
            'debug'=>$debug
        ]);
    }

    public function actionViewV3($id, $debug=false)
    {
        $model = $this->findModel($id);

        if ($model->canViewV3() == false) {
            throw new ForbiddenHttpException('Anda tidak diperbolehkan melakukan aksi ini');
        }

        $allKegiatanRhkUtama = $model->findAllKegiatanRhk([
            'id_kegiatan_rhk_jenis' => KegiatanRhkJenis::UTAMA,
            'id_induk_is_null' => true,
        ]);

        $allKegiatanRhkTambahan = $model->findAllKegiatanRhk([
            'id_kegiatan_rhk_jenis' => KegiatanRhkJenis::TAMBAHAN,
            'id_induk_is_null' => true,
        ]);

        $allSkpPerilakuJenis = SkpPerilakuJenis::find()
            ->all();

        return $this->render('view-v3', [
            'model' => $this->findModel($id),
            'allKegiatanRhkUtama' => $allKegiatanRhkUtama,
            'allKegiatanRhkTambahan' => $allKegiatanRhkTambahan,
            'allSkpPerilakuJenis' => $allSkpPerilakuJenis,
            'debug'=> $debug,
        ]);
    }

    public function actionIndexLampiran($id_pegawai=null)
    {
        $searchModel = new InstansiPegawaiSkpSearch();
        $searchModel->id_pegawai = $id_pegawai;
        $searchModel->load(Yii::$app->request->queryParams);

        if (Session::isPegawai()) {
            $searchModel->id_pegawai = Session::getIdPegawai();
        }

        $model = InstansiPegawaiSkp::find()
            ->joinWith(['instansiPegawai'])
            ->andWhere([
                'instansi_pegawai.id_pegawai' => $searchModel->id_pegawai,
                'instansi_pegawai_skp.tahun' => Session::getTahun(),
                'instansi_pegawai_skp.nomor' => $searchModel->nomor,
            ])
            ->one();

        return $this->render('index-lampiran', [
            'searchModel' => $searchModel,
            'model' => $model,
        ]);
    }

    public function actionViewBaru($id)
    {
        return $this->render('view-baru', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionExportPdfSkp($id)
    {
        $model = $this->findModel($id);
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

    public function actionExportPdfSkpV2($id)
    {
        $model = $this->findModel($id);
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

    public function actionExportPdfFormIiJf($id)
    {
        $model = $this->findModel($id);
        $content = $this->renderPartial('_pdf-form-ii-jf', ['model' => $model]);
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
            'options' => ['title' => 'Export PDF FORM II JF'],
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }

    /**
     * @throws \setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException
     * @throws \Mpdf\MpdfException
     * @throws \yii\base\InvalidConfigException
     * @throws \setasign\Fpdi\PdfParser\PdfParserException
     * @throws NotFoundHttpException
     * @throws \setasign\Fpdi\PdfParser\Type\PdfTypeException
     */
    public function actionExportPdfSkpV3($id)
    {
        $model = $this->findModel($id);

        $allKegiatanRhkUtama = $model->findAllKegiatanRhk([
            'id_kegiatan_rhk_jenis' => KegiatanRhkJenis::UTAMA,
            'id_induk_is_null' => true,
        ]);

        $allKegiatanRhkTambahan = $model->findAllKegiatanRhk([
            'id_kegiatan_rhk_jenis' => KegiatanRhkJenis::TAMBAHAN,
            'id_induk_is_null' => true,
        ]);

        $allKegiatanTahunan = KegiatanTahunan::find()
            ->joinWith(['kegiatanRhk'])
            ->andWhere([
                'kegiatan_tahunan.tahun' => $model->tahun,
                'kegiatan_tahunan.id_instansi_pegawai_skp' => $model->id,
                'kegiatan_tahunan.id_kegiatan_tahunan_versi' => 3,
            ])
            ->orderBy([
                'kegiatan_rhk.id' => SORT_ASC,
                'kegiatan_rhk.id_induk' => SORT_DESC,
            ])
            ->all();

        $allSkpPerilakuJenis = SkpPerilakuJenis::find()
            ->all();

        $content = $this->renderPartial('export-pdf-skp-v3', [
            'model' => $model,
            'allKegiatanRhkUtama' => $allKegiatanRhkUtama,
            'allKegiatanRhkTambahan' => $allKegiatanRhkTambahan,
            'allSkpPerilakuJenis' => $allSkpPerilakuJenis,
            'allKegiatanTahunan' => $allKegiatanTahunan,
        ]);

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
        }

        tr td {
            vertical-align: top;
        }

        .table-bordered td, .table-bordered th {
            border: 1px solid black;
            padding: 3px 5px 3px 5px;
        }

        .padding-0 td, .padding-0 th {
            padding: 0;
        }

CSS;

        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => [215.9, 330],
            'defaultFontSize' => '12',
            'defaultFont' => 'Courier',
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

    public function actionExportExcelSkp($id)
    {
        $model = $this->findModel($id);
        $pegawai = $model->pegawai;
        $instansiPegawai = $model->instansiPegawai;
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
        $sheet->setCellValue('D8', @$instansiPegawai->atasan->nama);
        $sheet->setCellValue('E8', '4');
        $sheet->setCellValue('F8', 'Jabatan');
        $sheet->setCellValue('I8', @$instansiPegawai->getNamaJabatan());

        $sheet->setCellValue('B9', '5');
        $sheet->setCellValue('C9', 'Perangkat Daerah');
        $sheet->setCellValue('D9', @$instansiPegawai->atasan->instansi->nama);
        $sheet->setCellValue('E9', '5');
        $sheet->setCellValue('F9', 'Perangkat Daerah');
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


        $path = '../files/';
        $filename = time() . '_ExportSkp.xlsx';
        $objWriter = \PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
        $objWriter->save($path . $filename);
        return $this->redirect(['/file/get', 'fileName' => $filename]);
    }

    public function actionRefresh($id=null)
    {
        $query = Pegawai::find();

        if(User::isPegawai()) {
            $id = User::getIdPegawai();
        }

        if($id != null) {
            $query->andWhere(['id'=>$id]);
        }

        $query->limit(100);

        foreach($query->all() as $pegawai) {
            $pegawai->refreshInstansiPegawaiSkp();
        }

        Yii::$app->session->setFlash('success','Daftar SKP Berhasil  Diperbarui');
        return $this->redirect(Yii::$app->request->referrer);

    }

    /**
     * Creates a new InstansiPegawaiSkp model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new InstansiPegawaiSkp();

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {

            $referrer = $_POST['referrer'];

            if($model->save()) {
                Yii::$app->session->setFlash('success','Data berhasil disimpan.');
                return $this->redirect($referrer);
            }

            Yii::$app->session->setFlash('error','Data gagal disimpan. Silahkan periksa kembali isian Anda.');

        }

        return $this->render('create', [
            'model' => $model,
            'referrer'=>$referrer
        ]);

    }

    /**
     * Updates an existing InstansiPegawaiSkp model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {

            $referrer = $_POST['referrer'];

            $model->setTanggalMulai();
            $model->setTanggalSelesai();

            if($model->save())
            {
                Yii::$app->session->setFlash('success','Data berhasil disimpan.');
                return $this->redirect($referrer);
            }

            Yii::$app->session->setFlash('error','Data gagal disimpan. Silahkan periksa kembali isian Anda.');


        }

        return $this->render('update', [
            'model' => $model,
            'referrer'=>$referrer
        ]);

    }

    /**
     * Deletes an existing InstansiPegawaiSkp model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (Session::isAdmin() == false) {
            throw new ForbiddenHttpException('Anda tidak memiliki akses untuk halaman ini');
        }

        $model = $this->findModel($id);

        if($model->softDelete()) {
            Yii::$app->session->setFlash('success','Data berhasil dihapus');
        } else {
            Yii::$app->session->setFlash('error','Data gagal dihapus');
        }

        return $this->redirect(Yii::$app->request->referrer);


    }

    /**
     * Finds the InstansiPegawaiSkp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return InstansiPegawaiSkp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InstansiPegawaiSkp::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function exportExcel($params)
    {
        $PHPExcel = new \PHPExcel();

        $PHPExcel->setActiveSheetIndex();

        $sheet = $PHPExcel->getActiveSheet();

        $sheet->getDefaultStyle()->getAlignment()->setWrapText(true);
        $sheet->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $setBorderArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => \PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );


        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);

        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'Id Instansi Pegawai');
        $sheet->setCellValue('C3', 'Tahun');
        $sheet->setCellValue('D3', 'Urutan');
        $sheet->setCellValue('E3', 'Nomor');
        $sheet->setCellValue('F3', 'Waktu Hapus');
        $sheet->setCellValue('G3', 'Id User Hapus');

        $PHPExcel->getActiveSheet()->setCellValue('A1', 'Data InstansiPegawaiSkp');

        $PHPExcel->getActiveSheet()->mergeCells('A1:G1');

        $sheet->getStyle('A1:G3')->getFont()->setBold(true);
        $sheet->getStyle('A1:G3')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $row = 3;
        $i=1;

        $searchModel = new InstansiPegawaiSkpSearch();

        foreach($searchModel->getQuerySearch($params)->all() as $data){
            $row++;
            $sheet->setCellValue('A' . $row, $i);
            $sheet->setCellValue('B' . $row, $data->id_instansi_pegawai);
            $sheet->setCellValue('C' . $row, $data->tahun);
            $sheet->setCellValue('D' . $row, $data->urutan);
            $sheet->setCellValue('E' . $row, $data->nomor);
            $sheet->setCellValue('F' . $row, $data->waktu_hapus);
            $sheet->setCellValue('G' . $row, $data->id_user_hapus);

            $i++;
        }

        $sheet->getStyle('A3:G' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D3:G' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E3:G' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


        $sheet->getStyle('C' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('A3:G' . $row)->applyFromArray($setBorderArray);

        $path = 'exports/';
        $filename = time() . '_DataPenduduk.xlsx';
        $objWriter = \PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
        $objWriter->save($path.$filename);
        return Yii::$app->getResponse()->redirect($path.$filename);
    }

    public function actionGetList()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $id_pegawai = $parents[0];
                $out = InstansiPegawaiSkp::getListDepdrop([
                    'id_pegawai' => $id_pegawai,
                ]);
                return Json::encode(['output' => $out, 'selected' => null]);
            }
        }
        return Json::encode(['output' => $out, 'selected' => '']);
    }

}
