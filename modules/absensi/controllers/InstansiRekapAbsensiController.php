<?php

namespace app\modules\absensi\controllers;

use Yii;
use app\modules\absensi\models\InstansiRekapAbsensi;
use app\modules\absensi\models\InstansiRekapAbsensiSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Instansi;
use app\models\User;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/**
 * InstansiRekapAbsensiController implements the CRUD actions for InstansiRekapAbsensi model.
 */
class InstansiRekapAbsensiController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all InstansiRekapAbsensi models.
     * @return mixed
     */
    public function actionIndex()
    {
        $instansiRekapAbsensiSearch = new InstansiRekapAbsensiSearch();
        $instansiRekapAbsensiSearch->status_aktif = 1;
        $dataProvider = $instansiRekapAbsensiSearch->search(Yii::$app->request->queryParams);

        if(Yii::$app->request->get('export')) {
            return $this->exportExcel(Yii::$app->request->queryParams);
        }

        return $this->render('index', [
            'instansiRekapAbsensiSearch' => $instansiRekapAbsensiSearch,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSetup($bulan)
    {
        foreach(Instansi::find()->all() as $instansi) {
            $query = InstansiRekapAbsensi::find();
            $query->andWhere([
                'id_instansi'=>$instansi->id,
                'bulan'=>$bulan,
                'tahun'=>User::getTahun()
            ]);

            $model = $query->one();

            if($model===null) {
                $model = new InstansiRekapAbsensi;
                $model->id_instansi = $instansi->id;
                $model->bulan = $bulan;
                $model->tahun = User::getTahun();
                $model->save();
            }
        }

        Yii::$app->session->setFlash('success','Setup perangkat daerah instansi berhasil dilakukan');
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Displays a single InstansiRekapAbsensi model.
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
     * Creates a new InstansiRekapAbsensi model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new InstansiRekapAbsensi();

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
     * Updates an existing InstansiRekapAbsensi model.
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
     * Deletes an existing InstansiRekapAbsensi model.
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

    public function actionRefresh($id, $force = 0)
    {
        $instansiRekapAbsensi = $this->findModel($id);

        $instansiRekapAbsensi->refresh($force);

        Yii::$app->session->setFlash('success','Data berhasil direfresh');
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionRefreshAll($bulan)
    {
        $query = InstansiRekapAbsensi::find();
        $query->joinWith(['instansi']);
        $query->andWhere([
            'instansi_rekap_absensi.bulan' => $bulan,
            'instansi_rekap_absensi.tahun' => User::getTahun(),
        ]);

        $allInstansiRekapAbsensi = $query->all();

        foreach($allInstansiRekapAbsensi as $instansiRekapAbsensi) {
            $instansiRekapAbsensi->refresh(false);
        }

        Yii::$app->session->setFlash('success','Data berhasil direfresh');
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the InstansiRekapAbsensi model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return InstansiRekapAbsensi the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InstansiRekapAbsensi::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function exportExcel($params)
    {
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


        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(40);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(20);

        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'Perangkat Daerah');
        $sheet->setCellValue('C3', 'Bulan');
        $sheet->setCellValue('D3', 'Tahun');
        $sheet->setCellValue('E3', 'Persen Hadir');
        $sheet->setCellValue('F3', 'Persen Tidak Hadir');
        $sheet->setCellValue('G3', 'Persen Tanpa Keterangan');
        $sheet->setCellValue('H3', 'Waktu Diperbarui');

        $sheet->setCellValue('A1', 'Data Rekap Kehadiran Perangkat Daerah');

        $sheet->mergeCells('A1:H1');

        $sheet->getStyle('A1:H3')->getFont()->setBold(true);
        $sheet->getStyle('A1:H3')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $row = 3;
        $i=1;

        $searchModel = new InstansiRekapAbsensiSearch();

        foreach($searchModel->getQuerySearch($params)->all() as $data){
            $row++;
            $sheet->setCellValue('A' . $row, $i);
            $sheet->setCellValue('B' . $row, @$data->instansi->nama);
            $sheet->setCellValue('C' . $row, $data->bulan);
            $sheet->setCellValue('D' . $row, $data->tahun);
            $sheet->setCellValue('E' . $row, $data->persen_hadir);
            $sheet->setCellValue('F' . $row, $data->persen_tidak_hadir);
            $sheet->setCellValue('G' . $row, $data->persen_tanpa_keterangan);
            $sheet->setCellValue('H' . $row, $data->waktu_diperbarui);

            $i++;
        }

        $sheet->getStyle('A3:H' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B3:B' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('C3:C' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D3:D' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E3:D' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('A3:H' . $row)->applyFromArray($setBorderArray);

        $path = '../files/';
        $filename = time() . '_ExportInstansiRekapAbsensi.xlsx';
        $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
        $writer->save($path . $filename);
        return $this->redirect(['/file/get', 'fileName' => $filename]);
    }

}
