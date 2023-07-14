<?php

namespace app\modules\kinerja\controllers;

use app\models\Pegawai;
use app\modules\kinerja\models\PegawaiRekapKinerja;
use app\modules\kinerja\models\PegawaiRekapKinerjaSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * PegawaiRekapKinerjaController implements the CRUD actions for PegawaiRekapKinerja model.
 */
class PegawaiRekapKinerjaController extends Controller
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
        ];
    }

    /**
     * Lists all PegawaiRekapKinerja models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PegawaiRekapKinerjaSearch();
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
     * Lists all PegawaiRekapKinerja models.
     * @return mixed
     */
    public function actionIndexV2()
    {
        $searchModel = new PegawaiRekapKinerjaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (Yii::$app->request->get('export')) {
            return $this->exportExcel(Yii::$app->request->queryParams);
        }

        return $this->render('index-v2', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
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
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->getColumnDimension('I')->setWidth(20);
        $sheet->getColumnDimension('J')->setWidth(20);

        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'Id Pegawai');
        $sheet->setCellValue('C3', 'Id Instansi');
        $sheet->setCellValue('D3', 'Bulan');
        $sheet->setCellValue('E3', 'Tahun');
        $sheet->setCellValue('F3', 'Potongan Skp');
        $sheet->setCellValue('G3', 'Potongan Ckhp');
        $sheet->setCellValue('H3', 'Potongan Total');
        $sheet->setCellValue('I3', 'Waktu Buat');
        $sheet->setCellValue('J3', 'Waktu Update');

        $PHPExcel->getActiveSheet()->setCellValue('A1', 'Data PegawaiRekapKinerja');

        $PHPExcel->getActiveSheet()->mergeCells('A1:J1');

        $sheet->getStyle('A1:J3')->getFont()->setBold(true);
        $sheet->getStyle('A1:J3')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $row = 3;
        $i = 1;

        $searchModel = new PegawaiRekapKinerjaSearch();

        foreach ($searchModel->getQuerySearch($params)->all() as $data) {
            $row++;
            $sheet->setCellValue('A' . $row, $i);
            $sheet->setCellValue('B' . $row, $data->id_pegawai);
            $sheet->setCellValue('C' . $row, $data->id_instansi);
            $sheet->setCellValue('D' . $row, $data->bulan);
            $sheet->setCellValue('E' . $row, $data->tahun);
            $sheet->setCellValue('F' . $row, $data->potongan_skp);
            $sheet->setCellValue('G' . $row, $data->potongan_ckhp);
            $sheet->setCellValue('H' . $row, $data->potongan_total);
            $sheet->setCellValue('I' . $row, $data->waktu_buat);
            $sheet->setCellValue('J' . $row, $data->waktu_update);

            $i++;
        }

        $sheet->getStyle('A3:J' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D3:J' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E3:J' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


        $sheet->getStyle('C' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('A3:J' . $row)->applyFromArray($setBorderArray);

        $path = 'exports/';
        $filename = time() . '_DataPenduduk.xlsx';
        $objWriter = \PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
        $objWriter->save($path . $filename);
        return Yii::$app->getResponse()->redirect($path . $filename);
    }

    /**
     * Displays a single PegawaiRekapKinerja model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $model->setPotonganTotal();
        $model->save();
        return $this->render('view', [
            'model' => $model,
        ]);
    }

        /**
     * Displays a single PegawaiRekapKinerja model.
     * @param integer $id
     * @return mixed
     */
    public function actionViewV2($id)
    {
        $model = $this->findModel($id);
        $model->setPotonganTotal();
        $model->save();
        return $this->render('view-v2', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the PegawaiRekapKinerja model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PegawaiRekapKinerja the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PegawaiRekapKinerja::find()->andWhere(['id' => $id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Creates a new PegawaiRekapKinerja model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PegawaiRekapKinerja();

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
            'referrer' => $referrer
        ]);

    }

    /**
     * Updates an existing PegawaiRekapKinerja model.
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
            'referrer' => $referrer
        ]);

    }

    /**
     * Deletes an existing PegawaiRekapKinerja model.
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

    public function actionRefreshSingle()
    {

    }

    public function actionRefresh($id_instansi, $bulan)
    {
        $query = Pegawai::find()
            ->andWhere(['id_instansi' => $id_instansi])
            ->all();
        foreach ($query as $pegawai) {
            $pegawai->updatePegawaiRekapKinerja($bulan);
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

}
