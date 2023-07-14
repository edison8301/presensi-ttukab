<?php

namespace app\modules\absensi\controllers;

use app\components\Helper;
use app\components\Session;
use app\models\User;
use app\modules\absensi\models\ExportPdf;
use app\modules\absensi\models\KetidakhadiranJenis;
use app\modules\absensi\models\PegawaiRekapAbsensi;
use app\modules\absensi\models\PegawaiRekapAbsensiSearch;
use kartik\mpdf\Pdf;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * PegawaiRekapAbsensiController implements the CRUD actions for PegawaiRekapAbsensi model.
 */
class PegawaiRekapAbsensiController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['izin', 'sakit', 'cuti', 'dinas-luar', 'tugas-belajar', 'tugas-kedinasan', 'alasan-teknis', 'tanpa-keterangan', 'view', 'create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return User::isAdmin() or User::isVerifikator();
                        }
                    ],
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return User::isAdmin() or User::isVerifikator() or User::isInstansi() OR
                                User::isAdminInstansi() OR Session::isPemeriksaAbsensi();
                        }
                    ],
                    [
                        'actions' => ['perawatan'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return User::isAdmin();
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all PegawaiRekapAbsensi models.
     * @return mixed
     */
    public function actionIndex()
    {
        $pegawaiRekapAbsensiSearch = new PegawaiRekapAbsensiSearch();
        $dataProvider = $pegawaiRekapAbsensiSearch->search(Yii::$app->request->queryParams);

        if(User::isInstansi()) {
            $pegawaiRekapAbsensiSearch->id_instansi = User::getIdInstansi();
        }

        if (Yii::$app->request->get('export')) {
            return $this->exportExcel(Yii::$app->request->queryParams);
        }

        if (Yii::$app->request->get('delete')) {
            $this->deleteInternal($pegawaiRekapAbsensiSearch);
            $url = Yii::$app->request->url;
            $url = str_replace('&delete=1', '', $url);
            Yii::$app->session->setFlash('success', 'Proses delete berhasil dilakukan');
            return $this->redirect($url);
            //return $this->refresh();
        }

        if (isset($_GET['export-pdf'])) {
            if ($pegawaiRekapAbsensiSearch->id_instansi == null) {
                Yii::$app->session->setFlash('danger', 'Silahkan pilih perangkat daerah terlebih dahulu');
                return $this->redirect(['index']);
            }

            return $this->exportPdfFinal($pegawaiRekapAbsensiSearch);
        }

        if (Yii::$app->request->get('refresh')) {
            if ($pegawaiRekapAbsensiSearch->id_instansi == null) {
                Yii::$app->session->setFlash('danger', 'Silahkan pilih perangkat daerah terlebih dahulu');
                return $this->redirect(['index']);
            }

            $this->refreshInternal($pegawaiRekapAbsensiSearch);
            $url = Yii::$app->request->url;
            $url = str_replace('&refresh=1', '', $url);
            Yii::$app->session->setFlash('success', 'Proses refresh berhasil dilakukan');
            return $this->redirect($url);
        }

        return $this->render('index', [
            'pegawaiRekapAbsensiSearch' => $pegawaiRekapAbsensiSearch,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $pegawaiRekapAbsensiSearch PegawaiRekapAbsensiSearch
     */
    protected function refreshInternal(PegawaiRekapAbsensiSearch $pegawaiRekapAbsensiSearch)
    {
        $pegawaiRekapAbsensiSearch->load(Yii::$app->request->queryParams);
        $tahun = $pegawaiRekapAbsensiSearch->tahun;
        $bulan = $pegawaiRekapAbsensiSearch->bulan;

        $datetime = \DateTime::createFromFormat('Y-n-d', $tahun . '-' . $bulan . '-01');

        $query = \app\models\InstansiPegawai::find();
        $query->andWhere(['id_instansi' => $pegawaiRekapAbsensiSearch->id_instansi]);
        $query->berlaku($datetime->format('Y-m-15'));
        $query->with('pegawai');

        $allInstansiPegawai = $query->all();

        foreach ($allInstansiPegawai as $instansiPegawai) {
            $instansiPegawai->pegawai->updatePegawaiRekapAbsensi($bulan);
        }

        /*
        foreach ($pegawaiRekapAbsensiSearch->findAllPegawaiByIdInstansi(true) as $pegawai) {
            $pegawai->getPotonganBulan($pegawaiRekapAbsensiSearch->bulan);
            $pegawai->updatePegawaiRekapAbsensi($pegawaiRekapAbsensiSearch->bulan);
        }
        */
    }

    protected function monitoring($id_ketidakhadiran_jenis)
    {
        if (User::isAdmin()) {
            $pegawaiRekapAbsensiSearch = new PegawaiRekapAbsensiSearch(['id_ketidakhadiran_jenis' => $id_ketidakhadiran_jenis]);
            $dataProvider = $pegawaiRekapAbsensiSearch->search(Yii::$app->request->queryParams);
            if (Yii::$app->request->get('export')) {
                return $this->exportExcelMonitoring(Yii::$app->request->queryParams, $id_ketidakhadiran_jenis);
            }
            if (Yii::$app->request->get('export-pdf')) {
                if ($pegawaiRekapAbsensiSearch->id_instansi == null) {
                    Yii::$app->session->setFlash('danger', 'Silahkan pilih perangkat daerah terlebih dahulu');
                    return $this->redirect(['index']);
                }

                return $this->exportPdfMonitoring($pegawaiRekapAbsensiSearch, $id_ketidakhadiran_jenis);
            }
            return $this->render('monitoring', [
                'pegawaiRekapAbsensiSearch' => $pegawaiRekapAbsensiSearch,
                'dataProvider' => $dataProvider,
                'id_ketidakhadiran_jenis' => $id_ketidakhadiran_jenis,
            ]);
        }
        throw ForbiddenHttpException("Error Processing Request", 403);
    }

    public function actionIzin()
    {
        return $this->monitoring(KetidakhadiranJenis::IZIN);
    }

    public function actionSakit()
    {
        return $this->monitoring(KetidakhadiranJenis::SAKIT);
    }

    public function actionCuti()
    {
        return $this->monitoring(KetidakhadiranJenis::CUTI);
    }

    public function actionDinasLuar()
    {
        return $this->monitoring(KetidakhadiranJenis::DINAS_LUAR);
    }

    public function actionTugasBelajar()
    {
        return $this->monitoring(KetidakhadiranJenis::TUGAS_BELAJAR);
    }

    public function actionTugasKedinasan()
    {
        return $this->monitoring(KetidakhadiranJenis::TUGAS_KEDINASAN);
    }

    public function actionAlasanTeknis()
    {
        return $this->monitoring(KetidakhadiranJenis::ALASAN_TEKNIS);
    }

    public function actionTanpaKeterangan()
    {
        return $this->monitoring(KetidakhadiranJenis::TANPA_KETERANGAN);
    }

    /**
     * Displays a single PegawaiRekapAbsensi model.
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
     * Creates a new PegawaiRekapAbsensi model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PegawaiRekapAbsensi();

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

    public function actionPerawatan($bulan=null,$tahun=null)
    {
        if($bulan==null) {
            $bulan = date('n');
        }

        if($tahun==null) {
            $tahun = date('Y');
        }

        return $this->render('perawatan',[
            'bulan'=>$bulan,
            'tahun'=>$tahun
        ]);
    }

    /**
     * Updates an existing PegawaiRekapAbsensi model.
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
     * Deletes an existing PegawaiRekapAbsensi model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'Data berhasil dihapus');
        } else {
            Yii::$app->session->setFlash('error', 'Data gagal dihapus');
        }

        return $this->redirect(Yii::$app->request->referrer);

    }

    /**
     * Finds the PegawaiRekapAbsensi model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PegawaiRekapAbsensi the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PegawaiRekapAbsensi::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @param $searchModel \app\modules\absensi\models\PegawaiRekapAbsensiSearch
     */
    public function deleteInternal($searchModel)
    {
        $query = $searchModel->getQuerySearch(Yii::$app->request->queryParams);

        foreach($query->all() as $data) {
            $data->delete();
        }
    }

    public function exportExcelMonitoring($params, $id_ketidakhadiran_jenis)
    {
        $PHPExcel = new \PHPExcel();

        $PHPExcel->setActiveSheetIndex();

        $sheet = $PHPExcel->getActiveSheet();

        $sheet->getDefaultStyle()->getAlignment()->setWrapText(true);
        $sheet->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $setBorderArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => \PHPExcel_Style_Border::BORDER_THIN,
                ),
            ),
        );

        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(40);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);

        $sheet->setCellValue('A3', 'No')
            ->setCellValue('B3', 'Pegawai')
            ->setCellValue('C3', 'Bulan')
            ->setCellValue('D3', 'Jumlah Hari Kerja')
            ->setCellValue('E3', 'Jumlah Hadir')
            ->setCellValue('F3', 'Jumlah Tidak Hadir');

        switch ($id_ketidakhadiran_jenis) {
            case (KetidakhadiranJenis::IZIN):
                $atribut = 'jumlah_izin';
                $sheet->setCellValue('G3', 'Jumlah Izin');
                break;
            case (KetidakhadiranJenis::SAKIT):
                $atribut = 'jumlah_sakit';
                $sheet->setCellValue('G3', 'Jumlah Sakit');
                break;
            case (KetidakhadiranJenis::CUTI):
                $atribut = 'jumlah_cuti';
                $sheet->setCellValue('G3', 'Jumlah Cuti');
                break;
            case (KetidakhadiranJenis::DINAS_LUAR):
                $atribut = 'jumlah_dinas_luar';
                $sheet->setCellValue('G3', 'Jumlah Dinas Luar');
                break;
            case (KetidakhadiranJenis::TUGAS_BELAJAR):
                $atribut = 'jumlah_tugas_belajar';
                $sheet->setCellValue('G3', 'Jumlah Tugas Belajar');
                break;
            case (KetidakhadiranJenis::TUGAS_KEDINASAN):
                $atribut = 'jumlah_tugas_kedinasan';
                $sheet->setCellValue('G3', 'Jumlah Tugas Kedinasan');
                break;
            case (KetidakhadiranJenis::ALASAN_TEKNIS):
                $atribut = 'jumlah_alasan_teknis';
                $sheet->setCellValue('G3', 'Jumlah Alasan Teknis');
                break;
            case (KetidakhadiranJenis::TANPA_KETERANGAN):
                $atribut = 'jumlah_tanpa_keterangan';
                $sheet->setCellValue('G3', 'Jumlah Tanpa Keterangan');
                break;
        }

        $PHPExcel->getActiveSheet()->setCellValue('A1', 'Data Monitoring Absensi');

        $PHPExcel->getActiveSheet()->mergeCells('A1:G1');

        $sheet->getStyle('A1:G3')->getFont()->setBold(true);
        $sheet->getStyle('A1:G3')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $row = 3;
        $i = 1;
        $searchModel = new PegawaiRekapAbsensiSearch();

        foreach ($searchModel->getQuerySearch($params)->all() as $data) {
            $row++;
            $sheet->setCellValue('A' . $row, $i)
                ->setCellValue('B' . $row, @$data->pegawai->nama)
                ->setCellValue('C' . $row, Helper::getBulanSingkat($data->bulan) . ' ' . $data->tahun)
                ->setCellValue('D' . $row, $data->jumlah_hari_kerja)
                ->setCellValue('E' . $row, $data->jumlah_hadir)
                ->setCellValue('F' . $row, $data->jumlah_tidak_hadir)
                ->setCellValue('G' . $row, $data->{$atribut});

            $i++;
        }

        $sheet->getStyle('A4:A' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C4:G' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('C' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('A3:G' . $row)->applyFromArray($setBorderArray);

        $path = 'exports/';
        $filename = time() . '_Data_Pegawai_Rekap_Absensi.xlsx';
        $objWriter = \PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
        $objWriter->save($path . $filename);
        return $this->redirect($path . $filename);
    }

    public function exportPdfMonitoring($searchModel, $id_ketidakhadiran_jenis)
    {
        $query = $searchModel->getQuerySearch(Yii::$app->request->queryParams);
        $query->orderBy(['id_golongan' => SORT_DESC]);

        $content = $this->renderPartial('_export-pdf-monitoring', [
            'query' => $query,
            'searchModel' => $searchModel,
            'id_ketidakhadiran_jenis' => $id_ketidakhadiran_jenis,
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
            'options' => ['title' => 'Export'],
            // call mPDF methods on the fly
            /*'methods' => [
        'SetHeader'=>['Krajee Report Header'],
        'SetFooter'=>['{PAGENO}'],
        ]*/
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
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
                    'style' => \PHPExcel_Style_Border::BORDER_THIN,
                ),
            ),
        );

        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->getColumnDimension('I')->setWidth(20);
        $sheet->getColumnDimension('J')->setWidth(20);
        $sheet->getColumnDimension('K')->setWidth(20);
        $sheet->getColumnDimension('L')->setWidth(20);
        $sheet->getColumnDimension('M')->setWidth(20);
        $sheet->getColumnDimension('N')->setWidth(20);
        $sheet->getColumnDimension('O')->setWidth(20);
        $sheet->getColumnDimension('P')->setWidth(20);
        $sheet->getColumnDimension('Q')->setWidth(20);
        $sheet->getColumnDimension('R')->setWidth(20);
        $sheet->getColumnDimension('S')->setWidth(20);
        $sheet->getColumnDimension('T')->setWidth(20);
        $sheet->getColumnDimension('U')->setWidth(20);
        $sheet->getColumnDimension('V')->setWidth(20);
        $sheet->getColumnDimension('W')->setWidth(20);
        $sheet->getColumnDimension('X')->setWidth(20);
        $sheet->getColumnDimension('Y')->setWidth(20);
        $sheet->getColumnDimension('Z')->setWidth(20);

        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'Id Pegawai');
        $sheet->setCellValue('C3', 'Bulan');
        $sheet->setCellValue('D3', 'Tahun');
        $sheet->setCellValue('E3', 'Id Instansi');
        $sheet->setCellValue('F3', 'Id Golongan');
        $sheet->setCellValue('G3', 'Jumlah Hari Kerja');
        $sheet->setCellValue('H3', 'Jumlah Hadir');
        $sheet->setCellValue('I3', 'Jumlah Tidak Hadir');
        $sheet->setCellValue('J3', 'Jumlah Izin');
        $sheet->setCellValue('K3', 'Jumlah Sakit');
        $sheet->setCellValue('L3', 'Jumlah Cuti');
        $sheet->setCellValue('M3', 'Jumlah Tugas Belajar');
        $sheet->setCellValue('N3', 'Jumlah Tugas Kedinasan');
        $sheet->setCellValue('O3', 'Jumlah Dinas Luar');
        $sheet->setCellValue('P3', 'Jumlah Tanpa Keterangan');
        $sheet->setCellValue('Q3', 'Jumlah Tidak Hadir Apel Pagi');
        $sheet->setCellValue('R3', 'Jumlah Tidak Hadir Apel Sore');
        $sheet->setCellValue('S3', 'Jumlah Tidak Hadir Upacara');
        $sheet->setCellValue('T3', 'Jumlah Tidak Hadir Senam');
        $sheet->setCellValue('U3', 'Jumlah Tidak Hadir Sidak');
        $sheet->setCellValue('V3', 'Persen Potongan Fingerprint');
        $sheet->setCellValue('W3', 'Persen Potongan Kegiatan');
        $sheet->setCellValue('X3', 'Persen Potongan Total');
        $sheet->setCellValue('Y3', 'Status Kunci');
        $sheet->setCellValue('Z3', 'Waktu Diperbarui');

        $PHPExcel->getActiveSheet()->setCellValue('A1', 'Data PegawaiRekapAbsensi');

        $PHPExcel->getActiveSheet()->mergeCells('A1:Z1');

        $sheet->getStyle('A1:Z3')->getFont()->setBold(true);
        $sheet->getStyle('A1:Z3')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $row = 3;
        $i = 1;

        $searchModel = new PegawaiRekapAbsensiSearch();

        foreach ($searchModel->getQuerySearch($params)->all() as $data) {
            $row++;
            $sheet->setCellValue('A' . $row, $i);
            $sheet->setCellValue('B' . $row, $data->id_pegawai);
            $sheet->setCellValue('C' . $row, $data->bulan);
            $sheet->setCellValue('D' . $row, $data->tahun);
            $sheet->setCellValue('E' . $row, $data->id_instansi);
            $sheet->setCellValue('F' . $row, $data->id_golongan);
            $sheet->setCellValue('G' . $row, $data->jumlah_hari_kerja);
            $sheet->setCellValue('H' . $row, $data->jumlah_hadir);
            $sheet->setCellValue('I' . $row, $data->jumlah_tidak_hadir);
            $sheet->setCellValue('J' . $row, $data->jumlah_izin);
            $sheet->setCellValue('K' . $row, $data->jumlah_sakit);
            $sheet->setCellValue('L' . $row, $data->jumlah_cuti);
            $sheet->setCellValue('M' . $row, $data->jumlah_tugas_belajar);
            $sheet->setCellValue('N' . $row, $data->jumlah_tugas_kedinasan);
            $sheet->setCellValue('O' . $row, $data->jumlah_dinas_luar);
            $sheet->setCellValue('P' . $row, $data->jumlah_tanpa_keterangan);
            $sheet->setCellValue('Q' . $row, $data->jumlah_tidak_hadir_apel_pagi);
            $sheet->setCellValue('R' . $row, $data->jumlah_tidak_hadir_apel_sore);
            $sheet->setCellValue('S' . $row, $data->jumlah_tidak_hadir_upacara);
            $sheet->setCellValue('T' . $row, $data->jumlah_tidak_hadir_senam);
            $sheet->setCellValue('U' . $row, $data->jumlah_tidak_hadir_sidak);
            $sheet->setCellValue('V' . $row, $data->persen_potongan_fingerprint);
            $sheet->setCellValue('W' . $row, $data->persen_potongan_kegiatan);
            $sheet->setCellValue('X' . $row, $data->persen_potongan_total);
            $sheet->setCellValue('Y' . $row, $data->status_kunci);
            $sheet->setCellValue('Z' . $row, $data->waktu_diperbarui);

            $i++;
        }

        $sheet->getStyle('A3:Z' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D3:Z' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E3:Z' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('C' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('A3:Z' . $row)->applyFromArray($setBorderArray);

        $path = 'exports/';
        $filename = time() . '_Data_Pegawai_Rekap_Absensi.xlsx';
        $objWriter = \PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
        $objWriter->save($path . $filename);
        return $this->redirect($path . $filename);
    }

    public function exportPdf($searchModel)
    {
        $query = $searchModel->getQuerySearch(Yii::$app->request->queryParams);
        $query->orderBy(['id_golongan' => SORT_DESC]);

        $content = $this->renderPartial('_export-pdf', [
            'query' => $query,
            'searchModel' => $searchModel,
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
            'options' => ['title' => 'Export'],
            // call mPDF methods on the fly
            /*'methods' => [
        'SetHeader'=>['Krajee Report Header'],
        'SetFooter'=>['{PAGENO}'],
        ]*/
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }

    public function exportPdfFinal($searchModel)
    {
        $query = $searchModel->getQuerySearch(Yii::$app->request->queryParams);
        $query->orderBy(['id_golongan' => SORT_DESC]);

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


        $content = $this->renderPartial('export-pdf-final', [
            'query' => $query,
            'searchModel' => $searchModel,
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
            'options' => ['title' => 'Export'],
            // call mPDF methods on the fly
            'methods' => [
                'SetHTMLHeader' => $this->renderPartial('_barcode', ['modelExportPdf' => $modelExportPdf]),
            ],
            /*'methods' => [
        'SetHeader'=>['Krajee Report Header'],
        'SetFooter'=>['{PAGENO}'],
        ]*/
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }

}
