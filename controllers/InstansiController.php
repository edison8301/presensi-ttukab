<?php

namespace app\controllers;

use app\components\Helper;
use app\models\InstansiPegawai;
use app\models\Jabatan;
use app\models\RekapInstansiBulan;
use app\models\RekapJenis;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Yii;
use app\components\OrgPeta;
use app\components\Session;
use app\models\Instansi;
use app\models\InstansiSearch;
use app\models\User;
use app\models\UserRole;
use kartik\mpdf\Pdf;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
/**
 * InstansiController implements the CRUD actions for Instansi model.
 */
class InstansiController extends Controller
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
                'class' => \yii\filters\AccessControl::class,
                'rules' => [
                    [
                        'actions'=>['create','update','delete','set-session', 'index-kegiatan', 'view-kegiatan'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function() { return User::isAdmin(); }
                    ],
                    [
                        'actions'=>['view','peta'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function() { return $this->accessView(); }
                    ],
                    [
                        'actions'=>['update'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function() { return $this->accessUpdate(); }
                    ],
                    [
                        'actions'=>['delete'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function() { return $this->accessDelete(); }
                    ],
                    [
                        'actions'=>['view-jabatan','view-induk'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function() { return $this->accessViewJabatan(); }
                    ],
                    [
                        'actions' => ['index', 'index-lokasi', 'index-induk'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function() { return $this->accessIndex(); }
                    ],
                    [
                        'actions'=>['profil', 'profil-jabatan'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function() { return $this->accessProfil(); }
                    ],
                    [
                        'actions'=>['export-excel-jabatan'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function() { return $this->accessExportExcelJabatan(); }
                    ],
                    [
                        'actions'=>[
                            'export-pdf-jabatan', 'view-pegawai-rekap-absensi', 'index-rekap-kinerja',
                            'view-rekap-kinerja', 'export-excel-rekap-ckhp-iki-seluruh-unit',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],

        ];
    }

    /**
     * Lists all Instansi models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InstansiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Instansi models.
     * @return mixed
     */
    public function actionIndexLokasi()
    {
        $searchModel = new InstansiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index-lokasi', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Instansi models.
     * @return mixed
     */
    public function actionIndexInduk()
    {
        $searchModel = new InstansiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index-induk', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Instansi models.
     * @return mixed
     */
    public function actionIndexRekapKinerja()
    {
        $searchModel = new InstansiSearch();
        $searchModel->bulan = date('n');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index-rekap-kinerja', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Instansi models.
     * @return mixed
     */
    public function actionIndexKegiatan()
    {
        $searchModel = new InstansiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index-kegiatan', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Instansi model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Displays a single Instansi model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionViewJabatan($id, $mode='kelas-jabatan', $status_tampil_is_null=false)
    {
        ini_set('max_execution_time', 300);

        $searchModel = new InstansiSearch();
        $searchModel->mode = $mode;
        $searchModel->bulan = date('n');

        if ($status_tampil_is_null == false) {
            $searchModel->status_tampil = Jabatan::TAMPIL;
        }

        $searchModel->load(Yii::$app->request->queryParams);

        return $this->render('view-jabatan', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Instansi model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionViewInduk($id)
    {
        $searchModel = new InstansiSearch();

        $searchModel->bulan = date('n');

        $searchModel->load(Yii::$app->request->queryParams);

        return $this->render('view-induk', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel
        ]);
    }

    /**
     * Displays a single Instansi model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionViewPegawaiRekapAbsensi($id, $bulan, $tahun)
    {
        $model = $this->findModel($id);

        $searchModel = new InstansiSearch([
            'bulan' => $bulan,
            'tahun' => $tahun
        ]);

        $datetime = \DateTime::createFromFormat('Y-n', $tahun.'-'.$bulan);

        $allInstansiPegawai = InstansiPegawai::find()
            ->with('pegawai')
            ->andWhere(['id_instansi' => $model->id])
            ->berlaku($datetime->format('Y-m-15'))
            ->all();

        return $this->render('view-pegawai-rekap-absensi', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'allInstansiPegawai' => $allInstansiPegawai,
            'bulan' => $bulan,
            'tahun' => $tahun
        ]);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionViewRekapKinerja($id, $bulan=null)
    {
        $model = $this->findModel($id);

        if ($bulan == null) {
            $bulan = date('n');
        }

        $model->bulan = $bulan;

        return $this->render('view-rekap-kinerja', [
            'model' => $model,
            'bulan' => $bulan,
        ]);
    }

    public function actionViewKegiatan($id)
    {
        $model = $this->findModel($id);

        return $this->render('view-kegiatan', [
            'model' => $model,
        ]);
    }

    public function actionProfilJabatan()
    {
        $id = User::getIdInstansi();
        return $this->actionViewJabatan($id);
    }

    /**
     * @param null $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionProfil($id=null)
    {
        $id = User::getIdInstansi();

        return $this->render('profil', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Instansi model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Instansi();

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {

            $referrer = $_POST['referrer'];

            if($model->save())
            {
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
     * Updates an existing Instansi model.
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
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionSetSession($id)
    {
        $query = User::find();
        $query->andWhere([
           'id_instansi'=>$id,
           'id_user_role'=>UserRole::INSTANSI
        ]);

        $user = $query->one();

        if($user!==null) {
            Yii::$app->user->login($user);
            Yii::$app->session->setFlash('success','Ganti session berhasil');
        } else {
            Yii::$app->session->setFlash('error','Ganti session GAGAL');
        }

        return $this->redirect(['/site/index']);
    }

    /**
     * Deletes an existing Instansi model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $model->status_hapus = date('Y-m-d H:i:s');

        if($model->save())
        {
            Yii::$app->session->setFlash('success','Data berhasil dihapus');
        } else {
            Yii::$app->session->setFlash('error','Data gagal dihapus');
        }

        return $this->redirect(Yii::$app->request->referrer);


    }

    /**
     * Finds the Instansi model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Instansi the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Instansi::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function accessProfil()
    {
        if(User::isInstansi()) {
            return true;
        }

        if(User::isAdminInstansi()) {
            return true;
        }

        return false;

    }

    public function accessExportExcelJabatan()
    {
        if(User::isAdmin()) {
            return true;
        }

        if(User::isInstansi()) {
            return true;
        }

        if(User::isAdminInstansi()) {
            return true;
        }

        if(User::isMapping()) {
            return true;
        }

        return false;

    }

    public function accessIndex()
    {
        if(User::isAdmin()) {
            return true;
        }

        if(User::isVerifikator()) {
            return true;
        }

        if(User::isMapping()) {
            return true;
        }

        if(Session::isOperatorStruktur()) {
            return true;
        }

        return false;
    }

    public function accessViewJabatan()
    {
        if(User::isAdmin()) {
            return true;
        }

        if(User::isVerifikator()) {
            return true;
        }

        if(User::isMapping()) {
            return true;
        }

        if(Session::isOperatorStruktur()) {
            return true;
        }

        return false;
    }

    public function accessView()
    {
        if(User::isAdmin()) {
            return true;
        }

        if(User::isMapping()) {
            return true;
        }

        return false;
    }

    public function accessUpdate()
    {
        if(User::isAdmin()) {
            return true;
        }

        return false;
    }

    public function accessDelete()
    {
        if(User::isAdmin()) {
            return true;
        }

        return false;
    }

    /**
     * Displays a single Instansi model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function actionExportExcelJabatan($id)
    {
        $model = $this->findModel($id);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(80);
        $sheet->getColumnDimension('C')->setWidth(15);

        $namaInstansi = ucwords(strtolower($model->nama));
        $arrayJabatan = $model->getManyJabatan()->all();
        $jumlahJabatan = count($arrayJabatan);

        $row = 1;
        $sheet->setCellValue("A$row","Perangkat Daerah : $namaInstansi");


        $row = $row+2;
        $sheet->setCellValue("A$row","No");
        $sheet->setCellValue("B$row","Nama Jabatan");
        $sheet->setCellValue("C$row","Nilai Jabatan");

        $sheet->getStyle("A$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("B$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("C$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $row++;
        $no = 1;
        foreach($model->findAllJabatanKepala(['arrayJabatan'=>$arrayJabatan]) as $jabatan) {

            $params = [
                'jabatan'=>$jabatan,
                'arrayJabatan'=>$arrayJabatan,
                'sheet'=>$sheet,
                'row'=>$row,
                'no'=>$no,
                'level'=>0
            ];

            $params = $this->exportExcelJabatanSub($params);
        }

        $writer = new Xls($spreadsheet);

        $time = date('Y-m-d H-i-s');

        $filename = "Daftar Jabatan $namaInstansi - $time";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xls"'); /*-- $filename is  xsl filename ---*/
        header('Cache-Control: max-age=0');

        ob_end_clean();
        $writer->save('php://output');
        $spreadsheet->disconnectWorksheets();

        unset($spreadsheet);

    }

    /**
     * @param $params
     * @var \app\models\Jabatan $jabatan
     */
    public function exportExcelJabatanSub($params)
    {
        /**
         * @var \app\models\Jabatan $jabatan
         * @var Worksheet $sheet
         */
        $jabatan = $params['jabatan'];
        $arrayJabatan = $params['arrayJabatan'];
        $sheet = $params['sheet'];
        $row = $params['row'];
        $no = $params['no'];
        $level = $params['level'];

        $space = '';
        /*
        for($i=1;$i<=$level;$i++) {
            $space .= "- ";
        }
        */

        $sheet->setCellValue("A$row",$no);
        $sheet->setCellValue("B$row",$space.$jabatan->nama);
        $sheet->setCellValue("C$row",$jabatan->nilai_jabatan);

        $row++;
        $no++;
        $params['sheet'] = $sheet;
        $params['row'] = $row;
        $params['no'] = $no;

        $level++;

        foreach($jabatan->findAllSub(['arrayJabatan'=>$arrayJabatan]) as $subjabatan) {

            $params = [
                'jabatan'=>$subjabatan,
                'arrayJabatan'=>$arrayJabatan,
                'sheet'=>$params['sheet'],
                'row'=>$params['row'],
                'no'=>$params['no'],
                'level'=>$level
            ];

            $params = $this->exportExcelJabatanSub($params);
        }

        return $params;
    }

    public function actionExportPdfJabatan($id)
    {
        // get your HTML raw content without any layouts or scripts
        $searchModel = new InstansiSearch();
        $searchModel->bulan = date('n');
        $searchModel->load(Yii::$app->request->queryParams);

        $model = $this->findModel($id);
        $namaInstansi = ucwords(strtolower($model->nama));
        $content = $this->renderPartial('export-pdf-jabatan', [
                     'model' => $model,
                     'searchModel' => $searchModel
                     ]);
        $cssInline = <<<CSS
        table {
            *border-collapse: collapse;
            border-spacing: 0;
            width: 100%;
            font-size: 11px;
            vertical-align: top;
        }
        table, th, td {
          border: 1px solid black;
        }
        .table-pdf th {
            border: 0px solid #0000;
            background-color: #eee;
            text-align: center;
        }
CSS;
        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8,
            'marginLeft' => 10,
            'marginRight' => 10,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            //'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => $cssInline,
             // set mPDF properties on the fly
            'options' => ['title' => 'Struktur Jabatan'],
             // call mPDF methods on the fly
            'methods' => [
                // 'SetHeader'=>['Krajee Report Header'],
                'SetFooter'=>[null],
            ]
        ]);

        $time = date('Y-m-d H-i-s');

        $filename = "Daftar Jabatan $namaInstansi - $time";

        $pdf->filename = $filename.'.pdf';

        // return the pdf output as per the destination setting
        return $pdf->render();
    }

    public function actionPeta($id, $id_jabatan = null)
    {
        $instansi = $this->findModel($id);

        if($id_jabatan!=null) {
            $induk = Jabatan::findOne($id_jabatan);
        } else {
            $induk = $instansi->manyJabatanKepala[0];
        }

        $params = [
            'id_kepala'=>$induk->id,
            'status_sekretariat' => false,
            'status_sekretaris' => false,
            'status_induk' => false,
            'status_kepala' => true
        ];

        $arrayJabatan = OrgPeta::getArrayJabatan($induk,$params);

        $peta = new OrgPeta(false);

        $return = OrgPeta::setNode($peta, $arrayJabatan, $level=0);

        $peta = $return['peta'];
        // VarDumper::dump($peta, 10, true);
        // die;
        header('Content-type: image/jpeg');
        $peta->getImageOutput();
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function actionExportExcelRekapCkhpIkiSeluruhUnit($bulan)
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 300);

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
        $sheet->getColumnDimension('B')->setWidth(60);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(20);

        $datetime = \DateTime::createFromFormat('Y-n-d', Session::getTahun() . '-' . $bulan . '-01');

        $sheet->setCellValue('A2', 'REKAPAN CKHP DAN CAPAIAN INDIKATOR KINERJA INDIVIDU');
        $sheet->setCellValue('A3', 'BULAN ' . strtoupper(Helper::getBulanLengkap($bulan)) . ' ' . Session::getTahun());

        $sheet->mergeCells('A2:G2');
        $sheet->mergeCells('A3:G3');
        $sheet->getStyle('A2:G3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A5', 'NO');
        $sheet->setCellValue('B5', 'NAMA PERANGKAT DAERAH');
        $sheet->setCellValue('C5', 'POTONGAN CKHP');
        $sheet->setCellValue('E5', 'POTONGAN IKI BULANAN');
        $sheet->setCellValue('G5', 'JUMLAH PEGAWAI');

        $sheet->setCellValue('C6', 'Per Orang');
        $sheet->setCellValue('D6', 'Persentase');
        $sheet->setCellValue('E6', 'Per Orang');
        $sheet->setCellValue('F6', 'Persentase');

        $sheet->mergeCells('C5:D5');
        $sheet->mergeCells('E5:F5');
        $sheet->mergeCells('G5:G6');

        $sheet->getStyle('A5:G6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $query = Instansi::find();
        $query->andWhere(['instansi.status_aktif' => 1]);
        $query->andWhere('id_induk is null');

        $allInstansi = $query->all();

        $no = 1;
        $row = 6;

        /* @var $instansi Instansi */
        foreach ($allInstansi as $instansi) {
            $row++;
            $this->renderRowRekapCkhpIki($spreadsheet, $instansi, $bulan, $row, $no++);
        }

        $sheet->getStyle("A7:A$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("C7:G$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A5:G$row")->applyFromArray($setBorderArray);

        $path = '../files/';
        $filename = time() . '_ExportRekapCkhpSeluruhUnit.xlsx';
        $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
        $writer->save($path . $filename);
        return $this->redirect(['/file/get', 'fileName' => $filename]);
    }

    protected function renderRowRekapCkhpIki(Spreadsheet $spreadsheet, Instansi $instansi, int $bulan, int &$row, $no=null, $indent=0)
    {
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->getStyle("B$row")->getAlignment()->setIndent(($indent*2));

        $sheet->setCellValue("A$row", $no);
        $sheet->setCellValue("B$row", $instansi->nama);
        $sheet->setCellValue("C$row", $instansi->getJumlahPegawaiPotonganCkhp($bulan));
        $sheet->setCellValue("D$row", $instansi->getPersenPegawaiPotonganCkhp($bulan) . '%');
        $sheet->setCellValue("E$row", $instansi->getJumlahPegawaiPotonganCkhpIki($bulan));
        $sheet->setCellValue("F$row", $instansi->getPersenPegawaiPotonganIki($bulan) . '%');
        $sheet->setCellValue("G$row", $instansi->countInstansiPegawaiByBulanTahun($bulan));

        $indent++;
        foreach ($instansi->findAllSub(['status_aktif' => 1]) as $sub) {
            $row++;
            $this->renderRowRekapCkhpIki($spreadsheet, $sub, $bulan, $row, null, $indent);
        }
    }
}
