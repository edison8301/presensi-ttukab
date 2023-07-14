<?php

namespace app\controllers;

use app\models\Golongan;
use app\models\ImportPegawaiGolonganForm;
use app\models\Pegawai;
use Yii;
use app\models\PegawaiGolongan;
use app\models\PegawaiGolonganSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\User;
use yii\web\UploadedFile;

/**
 * PegawaiGolonganController implements the CRUD actions for PegawaiGolongan model.
 */
class PegawaiGolonganController extends Controller
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
                            'import',
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
     * Lists all PegawaiGolongan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PegawaiGolonganSearch();
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
     * Displays a single PegawaiGolongan model.
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
     * Creates a new PegawaiGolongan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id_pegawai)
    {
        $model = new PegawaiGolongan();

        $referrer = Yii::$app->request->referrer;

        $model->id_pegawai = $id_pegawai;

        if ($model->load(Yii::$app->request->post()) AND $model->validate()) {

            $referrer = $_POST['referrer'];

            $model->setTanggalMulai();
            $model->setTanggalSelesai();

            if($model->save()) {
                $model->updateMundurTanggalSelesai();
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
     * Updates an existing PegawaiGolongan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if($model->tanggal_selesai == '9999-12-31') {
            $model->tanggal_selesai = null;
        }

        $referrer = Yii::$app->request->referrer;

        if($model->load(Yii::$app->request->post()) AND $model->validate()) {

            $referrer = $_POST['referrer'];

            $model->setTanggalMulai();
            $model->setTanggalSelesai();

            if($model->save())
            {
                $model->updateMundurTanggalSelesai();
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
     * Deletes an existing PegawaiGolongan model.
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

    public function actionGenerate()
    {
        if (Yii::$app->request->post()) {
            $data = Yii::$app->request->post('data');

            $arrayData = explode("\n", $data);

            foreach ($arrayData as $data) {
                $array = explode("\t", $data);
                $nip = trim($array[0]);
                $golongan  = trim($array[1]);

                $pegawai = Pegawai::findOne(['nip' => $nip]);
                $golongan = Golongan::findOne(['golongan' => $golongan]);

                if ($pegawai != null AND $golongan != null) {

                    $pegawaiGolongan = $pegawai->getManyPegawaiGolongan()
                        ->andWhere(['id_golongan' => $golongan->id])
                        ->andWhere(['tanggal_berlaku' => '2022-04-01'])
                        ->andWhere(['tanggal_mulai' => '2022-04-01'])
                        ->one();

                    if ($pegawaiGolongan != null) {
                        continue;
                    }

                    $model = new PegawaiGolongan();
                    $model->id_pegawai = $pegawai->id;
                    $model->id_golongan = $golongan->id;
                    $model->tanggal_berlaku = '2022-04-01';
                    $model->tanggal_mulai = '2022-04-01';

                    $model->setTanggalMulai();
                    $model->setTanggalSelesai();

                    if ($model->save()) {
                        $model->updateMundurTanggalSelesai();
                    }
                }
            }
        }

        return $this->render('generate');
    }

    public function actionImport()
    {
        $model = new ImportPegawaiGolonganForm();

        if ($model->load(Yii::$app->request->post())) {
            $model->berkasUploaded = UploadedFile::getInstance($model, 'berkas');
            if ($model->upload() && $model->validate() && $model->execute()) {
                Yii::$app->session->setFlash('success', 'Data berhasil disimpan.');
                return $this->redirect(['/pegawai-golongan/index']);
            }
            Yii::$app->session->setFlash('error', 'Data gagal disimpan. Silahkan periksa kembali isian Anda.');
        }

        return $this->render('import', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the PegawaiGolongan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PegawaiGolongan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PegawaiGolongan::findOne($id)) !== null) {
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

        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'Id Pegawai');
        $sheet->setCellValue('C3', 'Id Golongan');
        $sheet->setCellValue('D3', 'Tanggal Berlaku');
        $sheet->setCellValue('E3', 'Tanggal Mulai');
        $sheet->setCellValue('F3', 'Tanggal Selesai');

        $PHPExcel->getActiveSheet()->setCellValue('A1', 'Data PegawaiGolongan');

        $PHPExcel->getActiveSheet()->mergeCells('A1:F1');

        $sheet->getStyle('A1:F3')->getFont()->setBold(true);
        $sheet->getStyle('A1:F3')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $row = 3;
        $i=1;

        $searchModel = new PegawaiGolonganSearch();

        foreach($searchModel->getQuerySearch($params)->all() as $data){
            $row++;
            $sheet->setCellValue('A' . $row, $i);
            $sheet->setCellValue('B' . $row, $data->id_pegawai);
            $sheet->setCellValue('C' . $row, $data->id_golongan);
            $sheet->setCellValue('D' . $row, $data->tanggal_berlaku);
            $sheet->setCellValue('E' . $row, $data->tanggal_mulai);
            $sheet->setCellValue('F' . $row, $data->tanggal_selesai);

            $i++;
        }

        $sheet->getStyle('A3:F' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D3:F' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E3:F' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


        $sheet->getStyle('C' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('A3:F' . $row)->applyFromArray($setBorderArray);

        $path = 'exports/';
        $filename = time() . '_DataPenduduk.xlsx';
        $objWriter = \PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
        $objWriter->save($path.$filename);
        return Yii::$app->getResponse()->redirect($path.$filename);
    }

}
