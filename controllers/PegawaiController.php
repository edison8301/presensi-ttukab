<?php

namespace app\controllers;

use app\components\Helper;
use app\components\Session;
use app\models\InstansiPegawai;
use app\models\InstansiPegawaiSearch;
use app\models\KegiatanBulanSearch;
use app\models\KegiatanRealisasiSearch;
use app\models\KegiatanSearch;
use app\models\Pegawai;
use app\models\PegawaiExportForm;
use app\models\PegawaiInstansi;
use app\models\PegawaiSearch;
use app\models\User;
use app\models\UserRole;
use app\modules\absensi\models\KetidakhadiranStatus;
use app\modules\kinerja\models\InstansiPegawaiSkp;
use app\modules\kinerja\models\KegiatanTahunan;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * PegawaiController implements the CRUD actions for Pegawai model.
 */
class PegawaiController extends Controller
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
                        'actions' => ['view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['create', 'update', 'delete',
                            'set-jumlah-userinfo', 'set-status-pengajuan',
                            'login'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return User::isAdmin();
                        },
                    ],
                    [
                        'actions' => ['get-list-ajax'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['index', 'index-test', 'get-list', 'set-setuju-all',
                            'get-list-ajax'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return $this->accessIndex();
                        },
                    ],
                    [
                        'actions' => ['profil'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return $this->accessProfil();
                        },
                    ],
                    [
                        'actions' => ['maintenance-instansi-pegawai-skp',
                            'maintenance-instansi-pegawai-skp-proses'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return Session::isAdmin();
                        },
                    ],
                    [
                        'actions' => ['web-view-kehadiran'],
                        'allow' => true,
                        'roles' => ['*','@','?'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Pegawai models.
     * @return mixed
     */
    public function actionIndex($mode = 'pegawai')
    {
        $searchModel = new PegawaiSearch();
        $searchModel->mode = $mode;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $pegawaiExportForm = new PegawaiExportForm();
        $pegawaiExportForm->load(Yii::$app->request->queryParams);

        if (Yii::$app->request->get('export')) {
            $filename = time()."-data-pegawai-Keseluruhan-.xlsx";
            $pegawaiExportForm->exportDatPegawai(Yii::$app->request->queryParams, $filename);

            $path = 'exports/'.$filename;
            return $this->redirect($path);
            //$this->exportExcel(Yii::$app->request->queryParams);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'pegawaiExportForm' => $pegawaiExportForm
        ]);
    }

    public function actionIndexBawahan()
    {
        $searchModel = new PegawaiSearch();
        $searchModel->mode = 'bawahan';
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index-bawahan', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function exportExcel($params)
    {
        ini_set('max_execution_time', '300');
        ini_set('memory_limit', '1024M');
        $pegawaiSearch = @$params['PegawaiSearch'];

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
        $querySearch->with(['pegawai.golongan', 'instansi', 'jabatan']);
        if (!empty($pegawaiSearch['id_instansi'])) {
            $querySearch->andWhere(['pegawai.id_instansi' => $pegawaiSearch['id_instansi']]);
        }

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


        $filename = time()."Daftar-Pegawai-Bulan-".Helper::getBulanLengkap($instansiPegawaiSearch->bulan) . "-Tahun-" . $instansiPegawaiSearch->tahun.'-Keseluruhan-.xlsx';
        $path = 'exports/'.$filename;
        $writer = new Xlsx($spreadsheet);
        $writer->save($path);

        return $this->redirect($path);
    }

    public function actionIndexTest()
    {

        $pegawaiSearch = new PegawaiSearch();
        $pegawaiSearch->load(Yii::$app->request->queryParams);

        $query = PegawaiInstansi::find();
        $query->innerJoin('(
            SELECT MAX(tanggal_berlaku) tanggal_berlaku_terbaru, id_pegawai
            FROM pegawai_instansi
            WHERE tanggal_berlaku <= :tanggal
            GROUP BY id_pegawai
        ) as p2', 'pegawai_instansi.id_pegawai = p2.id_pegawai AND pegawai_instansi.tanggal_berlaku = p2.tanggal_berlaku')->groupBy(p2 . id_pegawais);
        $query->andWhere(['pegawai_instansi.id_instansi' => '1']);
        $query->andWhere('p2.tanggal_berlaku <= :tanggal', [':tanggal' => '2018-02-01']);
        $query->groupBy(['id_pegawai']);

        /*
        $sql = 'SELECT p1.* FROM pegawai_instansi p1
        JOIN
        (
        SELECT MAX(tanggal_berlaku) tanggal_berlaku_terbaru, id_pegawai
        FROM pegawai_instansi
        WHERE tanggal_berlaku <= :tanggal
        GROUP BY id_pegawai
        ) as p2 ON p1.id_pegawai = p2.id_pegawai AND p1.tanggal_berlaku = p2.tanggal_berlaku_terbaru
        WHERE p1.id_instansi = :id_instansi
        GROUP BY p1.id_pegawai';

        print "<textarea>";
        print_r($pegawaiInstansiArray);
        print "</textarea>";

        $query = PegawaiInstansi::find();
        $query->andWhere('tanggal_berlaku <= :tanggal',[
        ':tanggal'=>'2018-02-01',
        ]);

        $query->andWhere([
        'id_pegawai'=>'135'
        ]);

        $query->orderBy(['tanggal_berlaku'=>SORT_DESC]);
        $query->groupBy(['id_pegawai']);

        $pegawaiInstansiArray = $query->asArray()->all();

        foreach($pegawaiInstansiArray as $data) {
        print $data['id_pegawai'].'-'.$data['tanggal_berlaku'].'<br>';

        }
        //print $pegawaiInstansiArray['id_pegawai'].'-'.$pegawaiInstansiArray['tanggal_berlaku'].'<br>';

        $queryArray = new ArrayQuery();
        $queryArray->from($pegawaiInstansiArray);
        $queryArray->where(['id_instansi'=>'2']);

        $pegawaiInstansiArray = $queryArray->all();

        $idPegawaiArray = ArrayHelper::getColumn($pegawaiInstansiArray, function ($element) {
        $label = $element['id_pegawai'];
        return $label;
        });

        $query = Pegawai::find();
        $query->andWhere([
        'id'=>$idPegawaiArray
        ]);

        $dataProvider = new SqlDataProvider([
        'sql' => $sql,
        'params'=>[':tanggal'=>'2018-01-01',':id_instansi'=>2],
        'pagination'=>['pageSize'=>'10']
        ]);

         */

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => '10'],
        ]);

        return $this->render('index-test', [
            'searchModel' => $pegawaiSearch,
            'dataProvider' => $dataProvider,
        ]);

    }

    public function actionDashboard()
    {
        $nip = Yii::$app->user->identity->username;
        $model = \app\modules\kinerja\models\User::find()
            ->andWhere(['nip' => $nip])
            ->one();

        return $this->render('dashboard', [
            'model' => $model,
        ]);
    }

    public function actionAbsensi()
    {
        $id = Yii::$app->user->identity->username;
        $model = Pegawai::find()
            ->andWhere(['nip' => $id])
            ->one();

        return $this->render('absensi', ['model' => $model]);
    }

    /*
    public function actionAbsensi($id)
    {
    return $this->render('absensi', [
    'model' => $this->findModel($id),
    ]);
    }
     */

    public function actionSetNullKodePegawai()
    {
        $awal = strtotime('now');

        $query = Pegawai::find();

        foreach ($query->all() as $data) {
            $data->kode = null;
            $data->save();
        }

        $akhir = strtotime('now');

        print($akhir - $awal);
    }

    public function actionKinerja()
    {
        $id = Yii::$app->user->identity->username;
        $model = Pegawai::find()
            ->andWhere(['nip' => $id])
            ->one();

        return $this->render('kinerja', ['model' => $model]);
    }

    /**
     * @param null $id
     * @param bool $debug
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionProfil($id=null, $debug=false)
    {
        if(User::isPegawai()) {
            $id = User::getIdPegawai();
        }

        $model = $this->findModel($id);

        User::redirectDefaultPassword();

        return $this->render('profil', [
            'model' => $model,
            'debug'=>$debug
        ]);

    }

    public function accessProfil()
    {
        if(User::isPegawai()) {
            return true;
        }

        return false;

    }

    /*
    public function actionProfil()
    {
    $model = User::findPegawai();

    return $this->render('profil', [
    'model' => $model,
    ]);
    }
     */

    /**
     * Displays a single Pegawai model.
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if($model->accessView()==false) {
            throw new ForbiddenHttpException();
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionLogin($id)
    {
        $model = $this->findModel($id);

        if($model->user!==null) {
            Yii::$app->user->login($model->user);
            Yii::$app->session->setFlash('success','Ganti session berhasil');
        } else {
            Yii::$app->session->setFlash('error','Ganti session GAGAL');
        }


        return $this->redirect(['/site/index']);
    }

    /*
    public function actionSetKodePegawai()
    {
    $query = Pegawai::find();
    $query->andWhere('kode_pegawai IS NULL');
    foreach($query->all() as $data)
    {
    $refPegawai = \app\models\RefPegawai::findOne($data->id);
    $data->kode_pegawai = $refPegawai->nip;
    $data->save();
    }
    }
     */

    /**
     * Creates a new Pegawai model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Pegawai();

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {

            $referrer = $_POST['referrer'];

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Data berhasil disimpan.');
                return $this->redirect($referrer);
            }

            Yii::$app->session->setFlash('error', 'Data gagal disimpan. Silahkan periksa kembali isian Anda.');

        }

        return $this->render('create', [
            'model' => $model,
            'referrer' => $referrer,
        ]);

    }

    /**
     * Updates an existing Pegawai model.
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

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Data berhasil disimpan.');
                return $this->redirect($referrer);
            }

            Yii::$app->session->setFlash('error', 'Data gagal disimpan. Silahkan periksa kembali isian Anda.');

        }

        return $this->render('update', [
            'model' => $model,
            'referrer' => $referrer,
        ]);

    }

    /**
     * Deletes an existing Pegawai model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $model->status_hapus = date('Y-m-d H:i:s');

        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Data berhasil dihapus');
        } else {
            Yii::$app->session->setFlash('error', 'Data gagal dihapus');
        }

        return $this->redirect(Yii::$app->request->referrer);

    }

    /**
     * Finds the Pegawai model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pegawai the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pegawai::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionKegiatan()
    {
        $model = User::getPegawai();
        $searchModel = new KegiatanSearch();
        $dataProvider = $searchModel->searchByPegawai(Yii::$app->request->queryParams);
        return $this->render('kegiatan', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionKegiatanBulan()
    {
        $model = User::getPegawai();
        $searchModel = new KegiatanBulanSearch();
        $dataProvider = $searchModel->searchByPegawai(Yii::$app->request->queryParams);
        return $this->render('kegiatan-bulan', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionKegiatanRealisasi()
    {
        $model = User::getPegawai();
        $searchModel = new KegiatanRealisasiSearch();
        $dataProvider = $searchModel->searchByPegawai(Yii::$app->request->queryParams);
        return $this->render('kegiatan-realisasi', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSubordinat()
    {
        $searchModel = new PegawaiSearch();
        $dataProvider = $searchModel->searchSubordinat(Yii::$app->request->queryParams);

        return $this->render('subordinat', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionImportExcelDinasPendidikan()
    {
        $inputFileName = $_SERVER['DOCUMENT_ROOT'] . '\tunjangan\data\dinas-pendidikan.xlsx';
        if (!file_exists($inputFileName)) {
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
        $sheet = $PHPExcel->getActiveSheet();
        for ($i = 1; $i <= 2042; $i++) {
            $nip = str_replace(' ', '', trim($sheet->getCell('D' . $i)->getValue()));

            $pegawai = Pegawai::findOne(['nip' => $nip]);
            if ($pegawai !== null) {
                //id_instansi 33 adalah dinas pendidikan
                $pegawai->id_instansi = 33;
                $pegawai->save();

                echo $pegawai->nama . ' - ' . $pegawai->nip . ' ' . $pegawai->instansi->nama . '</br>';
            }
        }
    }

    public function actionPerawatan()
    {
        $inputFileName = $_SERVER['DOCUMENT_ROOT'] . '\tunjangan\data\dinas-pendidikan.xlsx';
        if (!file_exists($inputFileName)) {
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
        $sheet = $PHPExcel->getActiveSheet();

        for ($i = 2; $i <= 2042; $i++) {

            $gelar_depan = trim($sheet->getCell('A' . $i)->getValue());
            $nama = trim($sheet->getCell('B' . $i)->getValue());
            $gelar_belakang = trim($sheet->getCell('C' . $i)->getValue());
            $nip = str_replace(' ', '', trim($sheet->getCell('D' . $i)->getValue()));
            $eselon = trim($sheet->getCell('E' . $i)->getValue());
            $golongan = trim($sheet->getCell('F' . $i)->getValue());
            $nama_jabatan = trim($sheet->getCell('G' . $i)->getValue());
            $instansi = trim($sheet->getCell('H' . $i)->getValue());
            $sekolah = trim($sheet->getCell('I' . $i)->getValue());

            $modelPegawai = \app\models\Pegawai::findOne(['nip' => $nip]);
            if ($modelPegawai !== null) {
                $modelPegawai->gelar_depan = $gelar_depan;
                $modelPegawai->gelar_belakang = $gelar_belakang;
                // $modelPegawai->eselon = $eselon;
                $modelPegawai->golongan = $golongan;
                $modelPegawai->nama_jabatan = $nama_jabatan;

                $modelInstansi = \app\models\Instansi::findOne(['nama' => $sekolah]);
                if ($modelInstansi === null or $modelInstansi->nama !== $sekolah) {
                    $modelInstansi = new \app\models\Instansi;
                    $modelInstansi->nama = $sekolah;
                    $modelInstansi->id_instansi_jenis = 3;
                    $modelInstansi->id_induk = 33;
                    $modelInstansi->save(false);
                }

                $modelPegawai->id_instansi = $modelInstansi->id;
                $modelPegawai->save(false);

                echo $i . ' - ' . $modelPegawai->nama . '' . $modelPegawai->gelar_belakang . ' - ' . $modelPegawai->nip . ' - ' . $modelPegawai->golongan . ' - ' . $modelPegawai->eselon . ' - ' . $modelPegawai->instansi->nama . ' - ' . $modelPegawai->nama_jabatan . '</br>';
            }

        }
    }

    public function accessIndex()
    {
        if (User::isAdmin()) {
            return true;
        }

        if (User::isVerifikator()) {
            return true;
        }

        if (User::isMapping()) {
            return true;
        }

        return false;
    }

    public function actionGetList()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $id_eselon = $parents[0];
                $id_instansi = $parents[1];
                $id_golongan = $parents[2];
                if (!empty($_POST['depdrop_params'])) {
                    $params = $_POST['depdrop_params'];
                    $id_pegawai = $params[0];
                } else {
                    $id_pegawai = null;
                }
                $pegawai = Pegawai::findOne($id_pegawai);
                $out = Pegawai::getListJson($id_eselon, $id_instansi, $id_golongan);
                return Json::encode(['output' => $out, 'selected' => @$pegawai->id_atasan]);
            }
        }
        return Json::encode(['output' => $out, 'selected' => '']);
    }

    public function actionSetSetujuAll($id, $bulan)
    {
        if (!(User::isAdmin() or User::isVerifikator())) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $model = $this->findModel($id);
        $date = new \DateTime(User::getTahun() . '-' . $bulan . '-01');
        $query = $model->getManyKetidakhadiran()
            ->andWhere(['between', 'tanggal', $date->format('Y-m-01'), $date->format('Y-m-t')])
            ->proses()
            ->all();
        foreach ($query as $ketidakhadiran) {
            $ketidakhadiran->setKetidakhadiranStatus(KetidakhadiranStatus::SETUJU);
            $ketidakhadiran->save();
        }
        Yii::$app->session->setFlash('success', count($query) . " Ketidakhadiran Telah Berhasil Disetujui");
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionSetStatusPengajuan($id)
    {
        $model = $this->findModel($id);
        $model->getIsBatasPengajuan() ? $model->status_batas_pengajuan = 0 : $model->status_batas_pengajuan = 1;
        $model->save(false);
        Yii::$app->session->setFlash('success', "Status Batas Pengajuan Pegawai $model->nama Telah Berhasil Diubah");
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionGetListAjax($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = Pegawai::find()
                ->select(['id', 'CONCAT(nama, \' - \', nip) as text'])
                ->where(['or',
                    ['like', 'nama', $q],
                    ['like', 'nip', $q],
                ])
                ->limit(50);
            if (User::isPegawai()) {
                $query->andWhere(['id' => User::getIdPegawai()]);
            }

            if (User::isInstansi()) {
                $query->andWhere(['id_instansi' => User::getIdInstansi()]);
            }

            if (User::isVerifikator()) {
                $query->andWhere(['id_instansi' => User::getListIdInstansi()]);
            }
            $data = $query->asArray()->all();
            $out['results'] = array_values($data);
        }
        elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => Pegawai::findOne($id)->nama];
        }
        return $out;
    }

    public function actionMaintenanceInstansiPegawaiSkp($offset=0)
    {
        $query = Pegawai::find();
        $query->limit = 500;
        $query->offset = $offset;
        $i=1;
        $jumlah = 0;
        foreach($query->all() as $pegawai) {

            $jumlah_skp = $pegawai->getCountInstansiPegawaiSkp(['tahun'=>2020]);

            if($jumlah_skp > 1) {

                $nomor = 0;
                $id_instansi_pegawai_lama = 0;
                foreach($pegawai->findAllInstansiPegawaiSkp() as $skp) {

                    if($nomor == $skp->nomor) {
                        $jumlah++;

                        print $i.' - '.$pegawai->id.' - '.$pegawai->nama.' - '.$pegawai->nip.' - '.$jumlah_skp;
                        $id_instansi_pegawai_baru = $skp->id_instansi_pegawai;
                        print ' - '.$id_instansi_pegawai_lama.' - '.$id_instansi_pegawai_baru.' - ';
                        print Html::a('Proses',[
                            '/pegawai/maintenance-instansi-pegawai-skp-proses',
                            'id_pegawai'=>$pegawai->id,
                            'id_instansi_pegawai_lama'=>$id_instansi_pegawai_lama,
                            'id_instansi_pegawai_baru'=>$id_instansi_pegawai_baru
                        ]);
                        print '<br>';


                        $i++;
                    }
                    $id_instansi_pegawai_lama = $skp->id_instansi_pegawai;
                    $nomor = $skp->nomor;
                }
            }
        }
    }

    public function actionMaintenanceInstansiPegawaiSkpProses($id_pegawai,$id_instansi_pegawai_lama,$id_instansi_pegawai_baru)
    {
        $query = KegiatanTahunan::find();
        $query->andWhere([
            'id_instansi_pegawai'=>$id_instansi_pegawai_lama,
            'tahun'=>2020
        ]);

        foreach($query->all() as $data) {
            $data->updateAttributes([
                'id_instansi_pegawai'=>$id_instansi_pegawai_baru
            ]);
        }

        $querySkp = InstansiPegawaiSkp::find();
        $querySkp->andWhere([
            'id_instansi_pegawai'=>$id_instansi_pegawai_lama,
            'tahun'=>2020
        ]);
        foreach($querySkp->all() as $skp) {
            $skp->delete();
        }
    }

    public function actionWebViewKehadiran($nip)
    {
        $this->layout = 'webview';

        $nip = str_replace(' ','', $nip);

        $pegawai = Pegawai::findOne([
            'nip' => $nip
        ]);

        
        if($pegawai === null) {
            return $this->render('web-view-kehadiran-kosong');
        }

        $pegawaiSearch = new PegawaiSearch();
        $pegawaiSearch->tahun = User::getTahun();

        $bulan = date('n');
        if(date('j') <= 10 AND $bulan!=1) {
            $bulan = $bulan - 1;
        }

        $pegawaiSearch->bulan = $bulan;
        $pegawaiSearch->load(Yii::$app->request->queryParams);

        $pegawai->getPotonganBulan($pegawaiSearch->bulan);

        $pegawaiRekapAbsensi = $pegawai->updatePegawaiRekapAbsensi($pegawaiSearch->bulan);

        return $this->render('web-view-kehadiran', [
            'pegawai' => $pegawai,
            'pegawaiSearch' => $pegawaiSearch ,
            'pegawaiRekapAbsensi' => $pegawaiRekapAbsensi,
        ]);
    }
}
