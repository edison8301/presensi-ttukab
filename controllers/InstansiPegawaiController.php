<?php

namespace app\controllers;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Yii;
use app\components\Helper;
use app\models\Instansi;
use app\models\InstansiPegawai;
use app\models\InstansiPegawaiSearch;
use app\models\User;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
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
                        'actions' => ['index','update','view','create','export-excel-keseluruhan'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['delete','perawatan'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return User::isAdmin();
                        },
                    ],
                    [
                        'actions' => ['skp', 'get-list'],
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
        ];
    }

    /**
     * Lists all InstansiPegawai models.
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionIndex()
    {
        if(InstansiPegawai::accessIndex()==false) {
            throw new ForbiddenHttpException('The requested page does not exist.');
        }

        $searchModel = new InstansiPegawaiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (Yii::$app->request->get('export')) {
            return $this->exportExcel(Yii::$app->request->queryParams);
        }

        return $this->render('index', [
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
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new InstansiPegawai model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param $id_pegawai
     * @return mixed
     */
    public function actionCreate($id_instansi, $id_pegawai=null)
    {
        if(InstansiPegawai::accessCreate()==false) {

        }

        $model = new InstansiPegawai([
            'id_instansi' => $id_instansi,
            'id_pegawai' => $id_pegawai
        ]);

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {

            $referrer = Yii::$app->request->post('referrer');

            //Run validate sebelum setTanggalMulai dan setTanggalSelesai
            if($model->validate()) {
                
                $model->setTanggalMulai();
                $model->setTanggalSelesai();

                $model->nama_jabatan = $model->getNamaJabatan();
                $model->nama_jabatan_atasan = $model->getNamaJabatanAtasan();
                $model->nama_instansi = @$model->instansi->nama;

                if ($model->save()) {
                    $model->updateMundurTanggalSelesai();
                    Yii::$app->session->setFlash('success', 'Data berhasil disimpan.');
                    return $this->redirect($referrer);
                }
            }

            Yii::$app->session->setFlash('error', 'Data gagal disimpan. Silahkan periksa kembali isian Anda.');

        }

        return $this->render('create', [
            'model' => $model,
            'referrer' => $referrer,
        ]);

    }

    /**
     * Updates an existing InstansiPegawai model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if($model->accessUpdate()==false) {
            throw new ForbiddenHttpException();
        }

        if($model->tanggal_selesai=='9999-12-31') {
            $model->tanggal_selesai = null;
        }

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {

            $referrer = $_POST['referrer'];

            $model->setTanggalMulai();
            $model->setTanggalSelesai();

            if ($model->save()) {
                $model->updateMundurTanggalSelesai();
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
     * Deletes an existing InstansiPegawai model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->softDelete()) {
            Yii::$app->session->setFlash('success', 'Data berhasil dihapus');
        } else {
            Yii::$app->session->setFlash('error', 'Data gagal dihapus');
        }

        return $this->redirect(Yii::$app->request->referrer);

    }

    public function actionPerawatan()
    {
        return $this->render('perawatan');
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

    public function actionSkp()
    {
        /*
        $model = $this->findModel($id);
        $pegawai = Pegawai::findOne($id_pegawai);
        return $this->render('skp', [
            'model' => $model,
            'pegawai' => $pegawai,
        ]);
        */
    }

    public function actionGetList()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $id_pegawai = $parents[0];
                $out = InstansiPegawai::getListInstansi($id_pegawai);
                return Json::encode(['output' => $out, 'selected' => null]);
            }
        }
        return Json::encode(['output' => $out, 'selected' => '']);
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

        $querySearch = $instansiPegawaiSearch->getQuerySearch(Yii::$app->request->queryParams);
        $querySearch->limit(20);
        $querySearch->with(['pegawai.golongan', 'instansi', 'jabatan']);

        $sheet->setCellValue("A1", "Daftar Pegawai Bulan " . Helper::getBulanLengkap($instansiPegawaiSearch->bulan) . " Tahun " . User::getTahun());
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

        foreach ($querySearch->all() as $instansiPegawai) {
            $row++;
            $sheet->setCellValue("A$row", $i++)
                ->setCellValue("B$row", @$instansiPegawai->pegawai->nama)
                ->setCellValue("C$row", @$instansiPegawai->pegawai->nipFormat)
                ->setCellValue("D$row", @$instansiPegawai->pegawai->gender)
                ->setCellValue("E$row", @$instansiPegawai->pegawai->tempat_lahir)
                ->setCellValue("F$row", Helper::getTanggal(@$instansiPegawai->pegawai->tanggal_lahir))
                ->setCellValue("G$row", @$instansiPegawai->pegawai->alamat)
                ->setCellValue("H$row", @$instansiPegawai->pegawai->telepon)
                ->setCellValue("I$row", " ".@$instansiPegawai->pegawai->email)
                ->setCellValue("J$row", @$instansiPegawai->pegawai->instansi->nama)
                ->setCellValue("K$row", @$instansiPegawai->getNamaJabatan(false))
                ->setCellValue("L$row", @$instansiPegawai->pegawai->pegawaiGolongan->golongan->golongan)
                ->setCellValue("M$row", @$instansiPegawai->jabatan->getJenisJabatan())
                ->setCellValue("N$row", @$instansiPegawai->jabatan->kelas_jabatan)
                ->setCellValue("O$row", @$instansiPegawai->jabatan->nilai_jabatan)
                ->setCellValue("P$row", Helper::getTanggal(@$instansiPegawai->tanggal_berlaku))
                ->setCellValue("Q$row", Helper::getTanggal(@$instansiPegawai->tanggal_mulai))
                ->setCellValue("R$row", @$instansiPegawai->pegawai->getNamaShiftKerja())
                ->setCellValue("S$row", @$instansiPegawai->getTextStatusPegawai());
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
        $sheet->getStyle("F$header:S$row")
            ->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ]
            ]);
        $sheet->getStyle("A$header:S$row")
            ->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN
                    ]
                ],
            ]);

        $sheet->getStyle("A1:S$row")->getAlignment()->setWrapText(true);

        $path = '../files/';
        $filename = time().'_ExportPegawai.xlsx';
        $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
        $writer->save($path . $filename);
        return $this->redirect(['/file/get', 'fileName' => $filename]);
    }
}
