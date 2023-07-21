<?php

namespace app\controllers;

use Yii;
use app\models\KegiatanKetidakhadiran;
use app\models\KegiatanKetidakhadiranSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\User;

/**
 * KegiatanKetidakhadiranController implements the CRUD actions for KegiatanKetidakhadiran model.
 */
class KegiatanKetidakhadiranController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete'],
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
     * Lists all KegiatanKetidakhadiran models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new KegiatanKetidakhadiranSearch();
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
     * Displays a single KegiatanKetidakhadiran model.
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
     * Creates a new KegiatanKetidakhadiran model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new KegiatanKetidakhadiran();

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
     * Updates an existing KegiatanKetidakhadiran model.
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
     * Deletes an existing KegiatanKetidakhadiran model.
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
     * Finds the KegiatanKetidakhadiran model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return KegiatanKetidakhadiran the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = KegiatanKetidakhadiran::findOne($id)) !== null) {
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
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->getColumnDimension('I')->setWidth(20);

        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'Nip');
        $sheet->setCellValue('C3', 'Id Kegiatan Ketidakhadiran Jenis');
        $sheet->setCellValue('D3', 'Penjelasan');
        $sheet->setCellValue('E3', 'Checktime');
        $sheet->setCellValue('F3', 'Foto Pendukung');
        $sheet->setCellValue('G3', 'Created At');
        $sheet->setCellValue('H3', 'Updated At');
        $sheet->setCellValue('I3', 'Deleted At');

        $PHPExcel->getActiveSheet()->setCellValue('A1', 'Data KegiatanKetidakhadiran');

        $PHPExcel->getActiveSheet()->mergeCells('A1:I1');

        $sheet->getStyle('A1:I3')->getFont()->setBold(true);
        $sheet->getStyle('A1:I3')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $row = 3;
        $i=1;

        $searchModel = new KegiatanKetidakhadiranSearch();

        foreach($searchModel->getQuerySearch($params)->all() as $data){
            $row++;
            $sheet->setCellValue('A' . $row, $i);
            $sheet->setCellValue('B' . $row, $data->nip);
            $sheet->setCellValue('C' . $row, $data->id_kegiatan_ketidakhadiran_jenis);
            $sheet->setCellValue('D' . $row, $data->penjelasan);
            $sheet->setCellValue('E' . $row, $data->checktime);
            $sheet->setCellValue('F' . $row, $data->foto_pendukung);
            $sheet->setCellValue('G' . $row, $data->created_at);
            $sheet->setCellValue('H' . $row, $data->updated_at);
            $sheet->setCellValue('I' . $row, $data->deleted_at);
            
            $i++;
        }

        $sheet->getStyle('A3:I' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D3:I' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E3:I' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


        $sheet->getStyle('C' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('A3:I' . $row)->applyFromArray($setBorderArray);

        $path = 'exports/';
        $filename = time() . '_DataPenduduk.xlsx';
        $objWriter = \PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
        $objWriter->save($path.$filename);
        return Yii::$app->getResponse()->redirect($path.$filename);
    }

}
