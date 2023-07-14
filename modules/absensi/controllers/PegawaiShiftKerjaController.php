<?php

namespace app\modules\absensi\controllers;

use Yii;
use app\modules\absensi\models\PegawaiShiftKerja;
use app\modules\absensi\models\PegawaiShiftKerjaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;

/**
 * PegawaiShiftKerjaController implements the CRUD actions for PegawaiShiftKerja model.
 */
class PegawaiShiftKerjaController extends Controller
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
                        'actions'=>['index'],
                        'allow' => true,
                        'matchCallback' => function() { return $this->accessIndex(); }
                    ],
                    [
                        'actions'=>['create'],
                        'allow' => true,
                        'matchCallback' => function() { return $this->accessCreate(); }
                    ],
                    [
                        'actions'=>['update'],
                        'allow' => true,
                        'matchCallback' => function() { return $this->accessUpdate(); }
                    ],
                    [
                        'actions'=>['view'],
                        'allow' => true,
                        'matchCallback' => function() { return $this->accessView(); }
                    ],
                    [
                        'actions'=>['delete'],
                        'allow' => true,
                        'matchCallback' => function() { return $this->accessDelete(); }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all PegawaiShiftKerja models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PegawaiShiftKerjaSearch();
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
     * Displays a single PegawaiShiftKerja model.
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
     * Creates a new PegawaiShiftKerja model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id_pegawai)
    {
        $model = new PegawaiShiftKerja();

        $model->id_pegawai = $id_pegawai;

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
     * Updates an existing PegawaiShiftKerja model.
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
     * Deletes an existing PegawaiShiftKerja model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if($model->softDelete()) {
            Yii::$app->session->setFlash('success','Data berhasil dihapus');
        } else {
            Yii::$app->session->setFlash('error','Data gagal dihapus');
        }

        return $this->redirect(Yii::$app->request->referrer);


    }

    /**
     * Finds the PegawaiShiftKerja model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PegawaiShiftKerja the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PegawaiShiftKerja::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function accessIndex()
    {
        if(User::isAdmin()) {
            return true;
        }

        if(User::isVerifikator()) {
            return true;   
        }

        return false;
    }

    public function accessView()
    {
        if(User::isAdmin()) {
            return true;
        }

        if(User::isVerifikator()) {
            return true;   
        }

        if(User::isInstansi() AND @$this->pegwai->id_instansi == User::getIdInstansi()) {
            return true;
        }

        return false;
    }

    public function accessCreate()
    {
        if(User::isAdmin()) {
            return true;
        }

        if(User::isVerifikator()) {
            return true;   
        }

        if(User::isInstansi()) {
            return true;
        }

        if (User::isAdminInstansi()) {
            return true;
        }

        if(User::isGrup()) {
            return true;
        }

        return false;
    }

    public function accessUpdate()
    {
        if(User::isAdmin()) {
            return true;
        }

        if(User::isVerifikator()) {
            return true;   
        }

        if(User::isInstansi()) {
            return true;
        }

        if (User::isAdminInstansi()) {
            return true;
        }

        if(User::isGrup()) {
            return true;
        }

        return false;
    }

    public function accessDelete()
    {
        if(User::isAdmin()) {
            return true;
        }

        if(User::isVerifikator()) {
            return true;   
        }

        if(User::isInstansi()) {
            return true;
        }

        if (User::isAdminInstansi()) {
            return true;
        }

        if(User::isGrup()) {
            return true;
        }

        return false;
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

        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'Id Pegawai');
        $sheet->setCellValue('C3', 'Id Shift Kerja');
        $sheet->setCellValue('D3', 'Tanggal Berlaku');

        $PHPExcel->getActiveSheet()->setCellValue('A1', 'Data PegawaiShiftKerja');

        $PHPExcel->getActiveSheet()->mergeCells('A1:D1');

        $sheet->getStyle('A1:D3')->getFont()->setBold(true);
        $sheet->getStyle('A1:D3')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $row = 3;
        $i=1;

        $searchModel = new PegawaiShiftKerjaSearch();

        foreach($searchModel->getQuerySearch($params)->all() as $data){
            $row++;
            $sheet->setCellValue('A' . $row, $i);
            $sheet->setCellValue('B' . $row, $data->id_pegawai);
            $sheet->setCellValue('C' . $row, $data->id_shift_kerja);
            $sheet->setCellValue('D' . $row, $data->tanggal_berlaku);
            
            $i++;
        }

        $sheet->getStyle('A3:D' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D3:D' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E3:D' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


        $sheet->getStyle('C' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('A3:D' . $row)->applyFromArray($setBorderArray);

        $path = 'exports/';
        $filename = time() . '_DataPenduduk.xlsx';
        $objWriter = \PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
        $objWriter->save($path.$filename);
        return Yii::$app->getResponse()->redirect($path.$filename);
    }

}
