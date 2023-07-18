<?php

namespace app\controllers;

use app\components\Helper;
use app\models\Instansi;
use app\models\InstansiSearch;
use Yii;
use app\models\Kegiatan;
use app\models\KegiatanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\User;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use yii\data\Pagination;

/**
 * KegiatanController implements the CRUD actions for Kegiatan model.
 */
class KegiatanController extends Controller
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
                        'actions' => [
                            'index', 'view', 'create', 'update', 'delete',
                            'export-excel-rekap',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return User::isAdmin();
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ]
            ]
        ];
    }

    /**
     * Lists all Kegiatan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new KegiatanSearch();
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
     * Displays a single Kegiatan model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $searchModel = new InstansiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 10;

        return $this->render('view', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Kegiatan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Kegiatan();

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
     * Updates an existing Kegiatan model.
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
     * Deletes an existing Kegiatan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if($model->delete()) {
            Yii::$app->session->setFlash('success','Data berhasil dihapus');
        } else {
            Yii::$app->session->setFlash('error','Data gagal dihapus');
        }

        return $this->redirect(Yii::$app->request->referrer);


    }

    /**
     * Finds the Kegiatan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Kegiatan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Kegiatan::findOne($id)) !== null) {
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

        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'Nama');
        $sheet->setCellValue('C3', 'Tanggal');
        $sheet->setCellValue('D3', 'Jam Mulai Absen');
        $sheet->setCellValue('E3', 'Jam Selesai Absen');

        $PHPExcel->getActiveSheet()->setCellValue('A1', 'Data Kegiatan');

        $PHPExcel->getActiveSheet()->mergeCells('A1:E1');

        $sheet->getStyle('A1:E3')->getFont()->setBold(true);
        $sheet->getStyle('A1:E3')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $row = 3;
        $i=1;

        $searchModel = new KegiatanSearch();

        foreach($searchModel->getQuerySearch($params)->all() as $data){
            $row++;
            $sheet->setCellValue('A' . $row, $i);
            $sheet->setCellValue('B' . $row, $data->nama);
            $sheet->setCellValue('C' . $row, $data->tanggal);
            $sheet->setCellValue('D' . $row, $data->jam_mulai_absen);
            $sheet->setCellValue('E' . $row, $data->jam_selesai_absen);
            
            $i++;
        }

        $sheet->getStyle('A3:E' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D3:E' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E3:E' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


        $sheet->getStyle('C' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('A3:E' . $row)->applyFromArray($setBorderArray);

        $path = 'exports/';
        $filename = time() . '_DataPenduduk.xlsx';
        $objWriter = \PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
        $objWriter->save($path.$filename);
        return Yii::$app->getResponse()->redirect($path.$filename);
    }

    public function actionExportExcelRekap($id, $id_instansi)
    {
        $model = $this->findModel($id);
        $instansi = Instansi::findOne($id_instansi);

        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0);

        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getDefaultStyle()->getAlignment()->setWrapText(true);
        $spreadsheet->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $setBorderArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => \PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(20);

        $sheet->setCellValue('A1', 'DATA KEHADIRAN');

        $sheet->mergeCells('A1:D1');

        $sheet->getStyle('A1:D1')->getFont()->setBold(true);
        $sheet->getStyle('A1:D1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A3', 'Perangkat Daerah : ' . $instansi->nama);
        $sheet->setCellValue('A4', 'Kegiatan : ' . $model->nama);
        $sheet->setCellValue('A5', 'Tanggal : ' . Helper::getTanggal($model->tanggal));

        $sheet->mergeCells('A3:D3');
        $sheet->mergeCells('A4:D4');
        $sheet->mergeCells('A5:D5');

        $sheet->setCellValue('A7', 'NO');
        $sheet->setCellValue('B7', 'NAMA');
        $sheet->setCellValue('C7', 'NIP');
        $sheet->setCellValue('D7', 'STATUS');

        $sheet->getStyle('A7:D7')->getFont()->setBold(true);
        $sheet->getStyle('A7:D7')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $query = $instansi->getManyInstansiPegawai();
        $query->berlaku($model->tanggal);
        $query->groupBy('id_pegawai');

        $allInstansiPegawai = $query->all();

        $row = 8;
        $i=1;

        foreach ($allInstansiPegawai as $instansiPegawai) {
            $sheet->setCellValue("A$row", $i++);
            $sheet->setCellValue("B$row", @$instansiPegawai->pegawai->nama);
            $sheet->setCellValue("C$row", @$instansiPegawai->pegawai->nip);
            $sheet->setCellValue("D$row", $model->getStatusHadirPegawai([
                'id_pegawai' => $instansiPegawai->id_pegawai,
            ]));
        }

        $sheet->getStyle("A8:A$row:")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("C8:D$row:")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $path = Yii::getAlias('@app/files/');
        $filename = time() . '_DATA_KEHADIRAN.xlsx';

        ob_end_clean();
        $writer = new Xlsx($spreadsheet);
        $writer->save($path . $filename);

        return $this->redirect(['file/get', 'fileName' => $filename]);
    }

}
