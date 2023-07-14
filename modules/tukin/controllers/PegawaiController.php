<?php

namespace app\modules\tukin\controllers;

use app\components\Helper;
use app\modules\tukin\models\FilterTunjanganForm;
use app\modules\tukin\models\User;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Yii;
use app\modules\tukin\models\Pegawai;
use app\modules\tukin\models\PegawaiSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
                        'actions' => ['index', 'view', 'rekap', 'profil'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return User::isAdmin() || User::isMapping();
                        }
                    ],
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
     * Lists all Pegawai models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PegawaiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if(Yii::$app->request->get('export')) {
            return $this->exportExcel(Yii::$app->request->queryParams);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Pegawai model.
     * @param integer $id
     * @param bool $export
     * @return mixed
     */
    public function actionView($id, $export = false)
    {
        $filter = new FilterTunjanganForm($this->findModel($id));
        $filter->load(Yii::$app->request->queryParams);
        if ($filter->pegawai->jabatan === null) {
            return $this->renderContent('<h3>Tidak dapat mendapatkan kelas jabatan dan nilai jabatan karena jabatan untuk pegawai ini belum terakomodir</h3>');
        }
        if ($export) {
            return $this->exportRekapExcel(new PegawaiSearch(['bulan' => $filter->bulan]));
        }
        return $this->render('view', [
            'filter' => $filter,
            'model' => $filter->pegawai,
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

    public function actionRekap($export = false)
    {
        $searchModel = new PegawaiSearch();
        $searchModel->load(Yii::$app->request->queryParams);
        if ($export !== false) {
            return $this->exportRekapExcel($searchModel);
        }
        return $this->render('rekap', ['searchModel' => $searchModel]);
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

    public function actionProfil($export = false)
    {
        if (User::isPegawai()) {
            return $this->actionView(Yii::$app->user->identity->id_pegawai, $export);
        }
    }

    private function exportRekapExcel(PegawaiSearch $searchModel)
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0);
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(25);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(15);
        $sheet->getColumnDimension('I')->setWidth(15);
        $sheet->getColumnDimension('J')->setWidth(15);
        $sheet->getColumnDimension('K')->setWidth(15);
        $sheet->getColumnDimension('L')->setWidth(15);
        $sheet->getColumnDimension('M')->setWidth(15);
        $sheet->getColumnDimension('N')->setWidth(15);
        $sheet->getColumnDimension('O')->setWidth(15);
        $sheet->getColumnDimension('P')->setWidth(15);
        $sheet->getColumnDimension('Q')->setWidth(15);
        $sheet->getColumnDimension('R')->setWidth(15);

        $sheet->setCellValue("A1", "Rekap Tunjangan Kinerja " . @$searchModel->instansi->nama . " Bulan " . Helper::getBulanLengkap($searchModel->bulan) . " Tahun " . User::getTahun());
        $sheet->getStyle("A1")->getFont()->setBold(true);

        $headerAwal = $header = 3;
        $sheet->setCellValue("A$header", 'NO')
            ->setCellValue("B$header","NAMA PEGAWAI")
            ->setCellValue("C$header","NIP")
            ->setCellValue("D$header","NAMA JABATAN")
            ->setCellValue("E$header","NILAI JABATAN")
            ->setCellValue("F$header","KELAS JABATAN")
            ->setCellValue("G$header","FAKTOR\nPENYEIMBANG (%)")
            ->setCellValue("H$header","IDRp")
            ->setCellValue("I$header","REALISASI KINERJA (70%)")
            ->setCellValue("K$header","REALISASI PRESENSI (30%)")
            ->setCellValue("M$header","JUMLAH TUKIN")
            ->setCellValue("N$header","HUKUMAN DISIPLIN")
            ->setCellValue("R$header","JUMLAH AKHIR");

        $sheet->mergeCells("A$header:A" . ($header + 1))
            ->mergeCells("B$header:B" . ($header + 1))
            ->mergeCells("C$header:C" . ($header + 1))
            ->mergeCells("D$header:D" . ($header + 1))
            ->mergeCells("E$header:E" . ($header + 1))
            ->mergeCells("F$header:F" . ($header + 1))
            ->mergeCells("G$header:G" . ($header + 1))
            ->mergeCells("H$header:H" . ($header + 1))

            ->mergeCells("I$header:J$header")
            ->mergeCells("K$header:L$header")
            ->mergeCells("N$header:Q$header")

            ->mergeCells("M$header:M" . ($header + 1))
            ->mergeCells("R$header:R" . ($header + 1));

        $header++;
        $sheet->setCellValue("I$header", '%')
            ->setCellValue("J$header", '(Rupiah)')
            ->setCellValue("K$header", '%')
            ->setCellValue("L$header", '(Rupiah)')

            ->setCellValue("N$header", 'RINGAN')
            ->setCellValue("O$header", 'SEDANG')
            ->setCellValue("P$header", 'BERAT')
            ->setCellValue("Q$header", 'TOTAL');
        $header++;
        $sheet->setCellValue("A$header", 1)
            ->setCellValue("B$header", 2)
            ->setCellValue("C$header", 3)
            ->setCellValue("D$header", 4)
            ->setCellValue("E$header", 5)
            ->setCellValue("F$header", 6)
            ->setCellValue("G$header", 7)
            ->setCellValue("H$header", 8)
            ->setCellValue("I$header", 9)
            ->setCellValue("J$header", 10)
            ->setCellValue("K$header", 11)
            ->setCellValue("L$header", 12)
            ->setCellValue("M$header", 13)
            ->setCellValue("N$header", 14)
            ->setCellValue("O$header", 15)
            ->setCellValue("P$header", 16)
            ->setCellValue("Q$header", 17)
            ->setCellValue("R$header", 18);
        $sheet->getStyle("A$headerAwal:R$header")
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
                    'wrapText' => true,
                ],
            ]);
        $row = ++$header;
        $i = 1;
        foreach ($searchModel->searchPegawaiRekap() as $pegawai) {
            $rekap = $pegawai->findOrCreatePegawaiRekapTunjangan($searchModel->bulan);
            $sheet->setCellValue("A$row", $i++)
                ->setCellValue("B$row",$pegawai->nama)
                ->setCellValue("C$row","'$pegawai->nip");
            if ($pegawai->jabatan !== null) {
                $sheet
                    ->setCellValue("D$row", @$pegawai->jabatan->nama)
                    ->setCellValue("E$row", @$pegawai->kelasJabatan->getNilaiTengah())
                    ->setCellValue("F$row", @$pegawai->kelasJabatan->kelas_jabatan)
                    ->setCellValue("G$row", @$pegawai->jabatan->penyeimbang)
                    ->setCellValue("H$row", Yii::$app->params['idrp'])
                    ->setCellValue("I$row", @$rekap->getPersenKinerja())
                    ->setCellValue("J$row", @$pegawai->getRupiahKinerjaPersen($rekap))
                    ->setCellValue("K$row", @$rekap->getPersenAbsensi())
                    ->setCellValue("L$row", @$pegawai->getRupiahAbsensiPersen($rekap))
                    ->setCellValue("M$row", @$pegawai->getRupiahTukinPersen($searchModel->bulan))
                    ->setCellValue("N$row", @$pegawai->getPersenPotonganHukumanDisiplinRingan($searchModel->bulan))
                    ->setCellValue("O$row", @$pegawai->getPersenPotonganHukumanDisiplinSedang($searchModel->bulan))
                    ->setCellValue("P$row", @$pegawai->getPersenPotonganHukumanDisiplinBerat($searchModel->bulan))
                    ->setCellValue("Q$row", @$pegawai->getPotonganHukumanDisiplin($searchModel->bulan))
                    ->setCellValue("R$row", @$pegawai->getRupiahAkhirPersen($searchModel->bulan));
            }
            $row++;
        }

        $sheet->getStyle("A$header:A$row")
            ->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ]
            ]);
        $sheet->getStyle("E$header:M$row")
            ->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ]
            ]);
        $sheet->getStyle("N$header:Q$row")
            ->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ]
            ]);
        $sheet->getStyle("H$header:H$row")
            ->getNumberFormat()
            ->setFormatCode(Helper::getFormatRupiahExcelTanpaRp());
        $sheet->getStyle("J$header:J$row")
            ->getNumberFormat()
            ->setFormatCode(Helper::getFormatRupiahExcelTanpaRp());
        $sheet->getStyle("L$header:M$row")
            ->getNumberFormat()
            ->setFormatCode(Helper::getFormatRupiahExcelTanpaRp());
        $sheet->getStyle("R$header:R$row")
            ->getNumberFormat()
            ->setFormatCode(Helper::getFormatRupiahExcelTanpaRp());
        $sheet->getStyle("A$header:R$row")
            ->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN
                    ]
                ]
            ]);

        $path = '../files/';
        $filename = time().'_EXPORT_REKAP_KINERJA.xlsx';
        $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
        $writer->save($path . $filename);
        return $this->redirect(['file/get', 'fileName' => $filename]);
    }

}
