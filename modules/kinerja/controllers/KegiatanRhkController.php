<?php

namespace app\modules\kinerja\controllers;

use app\components\Session;
use app\modules\kinerja\models\InstansiPegawaiSkp;
use app\modules\kinerja\models\KegiatanRhkJenis;
use Yii;
use app\modules\kinerja\models\KegiatanRhk;
use app\modules\kinerja\models\KegiatanRhkSearch;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * KegiatanRhkController implements the CRUD actions for KegiatanRhk model.
 */
class KegiatanRhkController extends Controller
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
                        'allow' => true,
                        'roles' => ['@'],
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
     * Lists all KegiatanRhk models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new KegiatanRhkSearch();
        $searchModel->tahun = Session::getTahun();
        $searchModel->setNomorSkp();

        $allKegiatanRhkUtama = $searchModel->getQuerySearch(Yii::$app->request->queryParams)
            ->andWhere(['kegiatan_rhk.id_kegiatan_rhk_jenis' => KegiatanRhkJenis::UTAMA])
            ->andWhere('kegiatan_rhk.id_induk is null')
            ->all();

        $allKegiatanRhkTambahan = $searchModel->getQuerySearch(Yii::$app->request->queryParams)
            ->andWhere(['kegiatan_rhk.id_kegiatan_rhk_jenis' => KegiatanRhkJenis::TAMBAHAN])
            ->andWhere('kegiatan_rhk.id_induk is null')
            ->all();

        $instansiPegawaiSkp = InstansiPegawaiSkp::find()
            ->joinWith(['instansiPegawai'])
            ->andWhere([
                'instansi_pegawai.id_pegawai' => $searchModel->id_pegawai,
                'instansi_pegawai_skp.tahun' => $searchModel->tahun,
                'instansi_pegawai_skp.nomor' => $searchModel->nomor_skp,
            ])
            ->one();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'allKegiatanRhkUtama' => $allKegiatanRhkUtama,
            'allKegiatanRhkTambahan' => $allKegiatanRhkTambahan,
            'instansiPegawaiSkp' => $instansiPegawaiSkp,
        ]);
    }

    public function actionIndexBawahan()
    {
        $searchModel = new KegiatanRhkSearch();

        return $this->render('index-bawahan', [
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single KegiatanRhk model.
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
     * Creates a new KegiatanRhk model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionCreate($id_instansi_pegawai_skp=null, $id_induk=null)
    {
        if ($id_instansi_pegawai_skp == null AND $id_induk == null) {
            new BadRequestHttpException('id_instansi_pegawai_skp tidak boleh kosong');
        }

        $model = new KegiatanRhk();
        $model->tahun = Session::getTahun();
        $model->id_instansi_pegawai_skp = $id_instansi_pegawai_skp;
        $model->id_induk = $id_induk;
        $model->loadAttributes();

        if ($model->canUpdate() == false) {
            throw new ForbiddenHttpException('Anda tidak diperbolehkan melakukan aksi ini');
        }

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
     * Updates an existing KegiatanRhk model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->canUpdate() == false) {
            throw new ForbiddenHttpException('Anda tidak diperbolehkan melakukan aksi ini');
        }

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
     * Deletes an existing KegiatanRhk model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->canUpdate() == false) {
            throw new ForbiddenHttpException('Anda tidak diperbolehkan melakukan aksi ini');
        }

        if ($model->getJumlahKegiatanTahunan() > 0) {
            Yii::$app->session->setFlash('error','Rencana Hasil Kerja memiliki indikator, silahkan hapus terlebih dahulu');
            return $this->redirect(Yii::$app->request->referrer);
        }

        if ($model->getJumlahSub() > 0) {
            Yii::$app->session->setFlash('error','Rencana Hasil Kerja memiliki tahapan, silahkan hapus terlebih dahulu');
            return $this->redirect(Yii::$app->request->referrer);
        }

        if($model->softDelete()) {
            Yii::$app->session->setFlash('success','Data berhasil dihapus');
        } else {
            Yii::$app->session->setFlash('error','Data gagal dihapus');
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionMatriks()
    {
        $searchModel = new KegiatanRhkSearch();
        $searchModel->tahun = Session::getTahun();

        $allKegiatanRhkUtama = $searchModel->getQuerySearch(Yii::$app->request->queryParams)
            ->andWhere(['kegiatan_rhk.id_kegiatan_rhk_jenis' => KegiatanRhkJenis::UTAMA])
            ->andWhere('kegiatan_rhk.id_induk is null')
            ->all();

        $allKegiatanRhkTambahan = $searchModel->getQuerySearch(Yii::$app->request->queryParams)
            ->andWhere(['kegiatan_rhk.id_kegiatan_rhk_jenis' => KegiatanRhkJenis::TAMBAHAN])
            ->andWhere('kegiatan_rhk.id_induk is null')
            ->all();

        return $this->render('matriks', [
            'searchModel' => $searchModel,
            'allKegiatanRhkUtama' => $allKegiatanRhkUtama,
            'allKegiatanRhkTambahan' => $allKegiatanRhkTambahan,
        ]);
    }

    /**
     * Finds the KegiatanRhk model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return KegiatanRhk the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = KegiatanRhk::findOne($id)) !== null) {
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
        $sheet->setCellValue('B3', 'Tahun');
        $sheet->setCellValue('C3', 'Id Kegiatan Rhk Atasan');
        $sheet->setCellValue('D3', 'Id Pegawai');
        $sheet->setCellValue('E3', 'Id Instansi Pegawai');
        $sheet->setCellValue('F3', 'Id Kegiatan Rhk Jenis');

        $PHPExcel->getActiveSheet()->setCellValue('A1', 'Data KegiatanRhk');

        $PHPExcel->getActiveSheet()->mergeCells('A1:F1');

        $sheet->getStyle('A1:F3')->getFont()->setBold(true);
        $sheet->getStyle('A1:F3')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $row = 3;
        $i=1;

        $searchModel = new KegiatanRhkSearch();

        foreach($searchModel->getQuerySearch($params)->all() as $data){
            $row++;
            $sheet->setCellValue('A' . $row, $i);
            $sheet->setCellValue('B' . $row, $data->tahun);
            $sheet->setCellValue('C' . $row, $data->id_kegiatan_rhk_atasan);
            $sheet->setCellValue('D' . $row, $data->id_pegawai);
            $sheet->setCellValue('E' . $row, $data->id_instansi_pegawai);
            $sheet->setCellValue('F' . $row, $data->id_kegiatan_rhk_jenis);

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
