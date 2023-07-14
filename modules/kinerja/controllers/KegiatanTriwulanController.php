<?php

namespace app\modules\kinerja\controllers;

use app\components\Session;
use Yii;
use app\modules\kinerja\models\KegiatanTriwulan;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\User;
use app\modules\kinerja\models\KegiatanTahunan;
use app\modules\kinerja\models\KegiatanTahunanSearch;
use app\modules\kinerja\models\KegiatanTriwulanSearch;
use yii\helpers\Json;

/**
 * KegiatanTriwulanController implements the CRUD actions for KegiatanTriwulan model.
 */
class KegiatanTriwulanController extends Controller
{
    /**
     * @inheritdoc
     */
    // public function behaviors()
    // {
    //     return [
    //         'access' => [
    //             'class' => AccessControl::class,
    //             'rules' => [
    //                 [
    //                     'actions' => ['index', 'view', 'create', 'update', 'delete'],
    //                     'allow' => true,
    //                     'roles' => ['@'],
    //                     'matchCallback' => function ($rule, $action) {
    //                         return User::isAdmin();
    //                     }
    //                 ],
    //             ],
    //         ],
    //         'verbs' => [
    //             'class' => VerbFilter::class,
    //             'actions' => [
    //                 'delete' => ['POST'],
    //             ]
    //         ]
    //     ];
    // }
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all KegiatanTriwulan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new KegiatanTriwulanSearch();
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
     * Displays a single KegiatanTriwulan model.
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
     * Creates a new KegiatanTriwulan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new KegiatanTriwulan();
        $model->tahun = Session::getTahun();
        $model->id_pegawai = Session::getIdPegawai();
        $model->periode = 1;

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
     * Updates an existing KegiatanTriwulan model.
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
     * Deletes an existing KegiatanTriwulan model.
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
     * Finds the KegiatanTriwulan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return KegiatanTriwulan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = KegiatanTriwulan::findOne($id)) !== null) {
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
        $sheet->getColumnDimension('J')->setWidth(20);
        $sheet->getColumnDimension('K')->setWidth(20);

        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'Id Kegiatan Tahunan');
        $sheet->setCellValue('C3', 'Id Kegiatan Bulanan');
        $sheet->setCellValue('D3', 'Tahun');
        $sheet->setCellValue('E3', 'Bulan');
        $sheet->setCellValue('F3', 'Target');
        $sheet->setCellValue('G3', 'Realisasi');
        $sheet->setCellValue('H3', 'Persen Capaian');
        $sheet->setCellValue('I3', 'Deskripsi Capaian');
        $sheet->setCellValue('J3', 'Kendala');
        $sheet->setCellValue('K3', 'Tindak Lanjut');

        $PHPExcel->getActiveSheet()->setCellValue('A1', 'Data KegiatanTriwulan');

        $PHPExcel->getActiveSheet()->mergeCells('A1:K1');

        $sheet->getStyle('A1:K3')->getFont()->setBold(true);
        $sheet->getStyle('A1:K3')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $row = 3;
        $i=1;

        $searchModel = new KegiatanTriwulanSearch();

        foreach($searchModel->getQuerySearch($params)->all() as $data){
            $row++;
            $sheet->setCellValue('A' . $row, $i);
            $sheet->setCellValue('B' . $row, $data->id_kegiatan_tahunan);
            $sheet->setCellValue('C' . $row, $data->id_kegiatan_bulanan);
            $sheet->setCellValue('D' . $row, $data->tahun);
            $sheet->setCellValue('E' . $row, $data->bulan);
            $sheet->setCellValue('F' . $row, $data->target);
            $sheet->setCellValue('G' . $row, $data->realisasi);
            $sheet->setCellValue('H' . $row, $data->persen_capaian);
            $sheet->setCellValue('I' . $row, $data->deskripsi_capaian);
            $sheet->setCellValue('J' . $row, $data->kendala);
            $sheet->setCellValue('K' . $row, $data->tindak_lanjut);
            
            $i++;
        }

        $sheet->getStyle('A3:K' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D3:K' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E3:K' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


        $sheet->getStyle('C' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('A3:K' . $row)->applyFromArray($setBorderArray);

        $path = 'exports/';
        $filename = time() . '_DataPenduduk.xlsx';
        $objWriter = \PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
        $objWriter->save($path.$filename);
        return Yii::$app->getResponse()->redirect($path.$filename);
    }

    public function actionMatriks($id_instansi_pegawai = null)
    {
        $searchModel = new KegiatanTahunanSearch([
            'scenario' => KegiatanTahunanSearch::SCENARIO_PEGAWAI,
            'id_kegiatan_tahunan_versi' => 2,
        ]);
        User::redirectDefaultPassword();

        $searchModel->load(Yii::$app->request->queryParams);

        $query = $searchModel->querySearch(Yii::$app->request->queryParams);
        $query->andWhere(['id_kegiatan_tahunan_jenis' => 1]);
        $query->andWhere('kegiatan_tahunan.id_induk IS NULL');
        $query->andWhere(['instansi_pegawai.id_pegawai'=>User::getIdPegawai()]);
        $query->andWhere(['kegiatan_tahunan.tahun'=>User::getTahun()]);
        $query->with(['manyKegiatanBulanan','instansiPegawaiSkp']);

        $allKegiatanTahunanUtamaInduk = $query->all();

        $query2 = $searchModel->querySearch(Yii::$app->request->queryParams);
        $query2->andWhere(['id_kegiatan_tahunan_jenis' => 2]);
        $query2->andWhere('kegiatan_tahunan.id_induk IS NULL');
        $query2->andWhere(['instansi_pegawai.id_pegawai'=>User::getIdPegawai()]);
        $query2->andWhere(['kegiatan_tahunan.tahun'=>User::getTahun()]);
        $query2->with(['manyKegiatanBulanan','instansiPegawaiSkp']);

        $allKegiatanTahunanTambahanInduk = $query2->all();

        // $searchModel = new KegiatanTriwulanSearch([
        //     'scenario' => KegiatanTriwulanSearch::SCENARIO_PEGAWAI,
        // ]);
        // $searchModel->load(Yii::$app->request->queryParams);

        // if(User::isPegawai()){
        //     $searchModel->id_pegawai = Session::getIdPegawai();
        // }

        // $allKegiatanTriwulan = KegiatanTriwulan::findAll([
        //     'id_pegawai' => Session::getIdPegawai()
        // ]);

        // $model = new KegiatanTriwulan();
        // $model->findOrCreateKegiatanTriwulan();

        return $this->render('matriks', [
            'allKegiatanTahunanUtamaInduk' => $allKegiatanTahunanUtamaInduk,
            'allKegiatanTahunanTambahanInduk' => $allKegiatanTahunanTambahanInduk,
            'searchModel'=>$searchModel
        ]);
    }

    public function actionEditableUpdate()
    {
         if (Yii::$app->request->post('hasEditable')) {

             if(Session::isPemeriksaKinerja()) {
                 $out = Json::encode(['output' => '', 'message' => 'Anda tidak memiliki akses']);
                 return $out;
             }

             $out = Json::encode(['output'=>'', 'message'=>'']);

             $id = Yii::$app->request->post('editableKey');
             $model = KegiatanTriwulan::findOne($id);

             if ($model !== null) {
                 $posted = Yii::$app->request->post();
                 $post = ['KegiatanTriwulan' => $posted];
                 $msg = '';
                 if ($model->load($post)) {
                     if (!$model->save()) {
                         $msg = implode(',', $model->firstErrors);
                    }
                }

                $out = Json::encode(['output' => '', 'message' => $msg]);
            }

            return $out;
        }
    }

}
