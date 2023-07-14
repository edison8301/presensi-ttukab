<?php

namespace app\modules\kinerja\controllers;

use app\components\Session;
use Yii;
use app\modules\kinerja\models\User;
use app\modules\kinerja\models\KegiatanBulanan;
use app\modules\kinerja\models\KegiatanBulananSearch;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use function implode;

/**
 * KegiatanBulanController implements the CRUD actions for KegiatanBulan model.
 */
class KegiatanBulananController extends Controller
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
                ],
            ],
        ];
    }

    /**
     * Lists all KegiatanBulan models.
     * @return mixed
     */
    public function actionIndex($mode='pegawai')
    {
        \app\models\User::redirectDefaultPassword();

        $searchModel = new KegiatanBulananSearch();
        $searchModel->id_kegiatan_tahunan_versi = 1;
        $searchModel->mode = $mode;

        $bulan = date('n');
        if(date('j') <= 10 AND $bulan != 1) {
            $bulan = $bulan - 1;
        }

        $searchModel->bulan = $bulan;

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
     * Lists all KegiatanBulan models.
     * @return mixed
     */
    public function actionIndexV2($mode='pegawai')
    {
        \app\models\User::redirectDefaultPassword();

        $searchModel = new KegiatanBulananSearch();
        $searchModel->search(Yii::$app->request->queryParams);
        $searchModel->id_kegiatan_tahunan_versi = 2;
        $searchModel->mode = $mode;

        $bulan = date('n');
        if(date('j') <= 10 AND $bulan != 1) {
            $bulan = $bulan - 1;
        }

        $searchModel->bulan = $bulan;
        $id_pegawai = $searchModel->id_pegawai;

        if(\app\models\User::isPegawai() AND $mode=='pegawai') {
            $id_pegawai = \app\models\User::getIdPegawai();
        }

        $query = $searchModel->querySearch(Yii::$app->request->queryParams);
        $query->andWhere(['kegiatan_tahunan.id_pegawai' => $id_pegawai]);
        $query->andWhere(['kegiatan_tahunan.id_kegiatan_tahunan_jenis' => 1]);
        $query->andWhere(['instansi_pegawai.status_plt' => 0]);

        $allKegiatanBulanan = $query->all();

        $queryTambahan = $searchModel->querySearch(Yii::$app->request->queryParams);
        $queryTambahan->andWhere(['kegiatan_tahunan.id_pegawai' => $id_pegawai]);
        $queryTambahan->andWhere(['kegiatan_tahunan.id_kegiatan_tahunan_jenis' => 2]);
        $queryTambahan->andWhere(['instansi_pegawai.status_plt' => 0]);

        $allKegiatanBulananTambahan = $queryTambahan->all();

        $queryPlt = $searchModel->querySearch(Yii::$app->request->queryParams);
        $queryPlt->andWhere(['kegiatan_tahunan.id_pegawai' => $id_pegawai]);
        $queryPlt->andWhere(['instansi_pegawai.status_plt' => 1]);

        $allKegiatanBulananPlt = $queryPlt->all();

        if(Yii::$app->request->get('export')) {
            return $this->exportExcel(Yii::$app->request->queryParams);
        }

        return $this->render('index-v2', [
            'searchModel' => $searchModel,
            'allKegiatanBulanan' => $allKegiatanBulanan,
            'allKegiatanBulananTambahan' => $allKegiatanBulananTambahan,
            'allKegiatanBulananPlt' => $allKegiatanBulananPlt,
        ]);
    }

    /**
     * Lists all KegiatanBulan models.
     * @return mixed
     */
    public function actionIndexV3($mode='pegawai')
    {
        \app\models\User::redirectDefaultPassword();

        $searchModel = new KegiatanBulananSearch();
        $searchModel->search(Yii::$app->request->queryParams);
        $searchModel->id_kegiatan_tahunan_versi = 3;
        $searchModel->mode = $mode;

        $bulan = date('n');
        if(date('j') <= 10 AND $bulan != 1) {
            $bulan = $bulan - 1;
        }

        $searchModel->bulan = $bulan;
        $id_pegawai = $searchModel->id_pegawai;

        if(\app\models\User::isPegawai() AND $mode=='pegawai') {
            $id_pegawai = \app\models\User::getIdPegawai();
        }

        $query = $searchModel->querySearch(Yii::$app->request->queryParams);
        $query->andWhere(['kegiatan_tahunan.id_pegawai' => $id_pegawai]);
        $query->andWhere(['kegiatan_tahunan.id_kegiatan_tahunan_jenis' => 1]);
        $query->andWhere(['instansi_pegawai.status_plt' => 0]);

        $allKegiatanBulanan = $query->all();

        $queryTambahan = $searchModel->querySearch(Yii::$app->request->queryParams);
        $queryTambahan->andWhere(['kegiatan_tahunan.id_pegawai' => $id_pegawai]);
        $queryTambahan->andWhere(['kegiatan_tahunan.id_kegiatan_tahunan_jenis' => 2]);
        $queryTambahan->andWhere(['instansi_pegawai.status_plt' => 0]);

        $allKegiatanBulananTambahan = $queryTambahan->all();

        $queryPlt = $searchModel->querySearch(Yii::$app->request->queryParams);
        $queryPlt->andWhere(['kegiatan_tahunan.id_pegawai' => $id_pegawai]);
        $queryPlt->andWhere(['instansi_pegawai.status_plt' => 1]);

        $allKegiatanBulananPlt = $queryPlt->all();

        if(Yii::$app->request->get('export')) {
            return $this->exportExcel(Yii::$app->request->queryParams);
        }

        return $this->render('index-v3', [
            'searchModel' => $searchModel,
            'allKegiatanBulanan' => $allKegiatanBulanan,
            'allKegiatanBulananTambahan' => $allKegiatanBulananTambahan,
            'allKegiatanBulananPlt' => $allKegiatanBulananPlt,
        ]);
    }

    /**
     * Lists all KegiatanBulan models.
     * @return mixed
     */
    public function actionIndexBawahan()
    {
        \app\models\User::redirectDefaultPassword();

        $searchModel = new KegiatanBulananSearch();
        $searchModel->mode = 'atasan';
        $searchModel->id_kegiatan_tahunan_versi = 1;
        $dataProvider = $searchModel->searchBawahan(Yii::$app->request->queryParams);

        if(Yii::$app->request->get('export')) {
            return $this->exportExcel(Yii::$app->request->queryParams);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all KegiatanBulan models.
     * @return mixed
     */
    public function actionIndexBawahanV2()
    {
        \app\models\User::redirectDefaultPassword();

        $searchModel = new KegiatanBulananSearch();
        $searchModel->mode = 'atasan';
        $searchModel->id_kegiatan_tahunan_versi = 2;
        $dataProvider = $searchModel->searchBawahan(Yii::$app->request->queryParams);

        $query = $searchModel->getQueryBawahan(Yii::$app->request->queryParams);
        $query->andWhere(['kegiatan_tahunan.id_kegiatan_tahunan_jenis' => 1]);
        $query->andWhere(['instansi_pegawai.status_plt' => 0]);

        $allKegiatanBulanan = $query->all();

        $queryTambahan = $searchModel->getQueryBawahan(Yii::$app->request->queryParams);
        $queryTambahan->andWhere(['kegiatan_tahunan.id_kegiatan_tahunan_jenis' => 2]);
        $queryTambahan->andWhere(['instansi_pegawai.status_plt' => 0]);

        $allKegiatanBulananTambahan = $queryTambahan->all();

        $queryPlt = $searchModel->getQueryBawahan(Yii::$app->request->queryParams);
        $queryPlt->andWhere(['instansi_pegawai.status_plt' => 1]);

        $allKegiatanBulananPlt = $queryPlt->all();

        if(Yii::$app->request->get('export')) {
            return $this->exportExcel(Yii::$app->request->queryParams);
        }

        return $this->render('index-v2', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'allKegiatanBulanan' => $allKegiatanBulanan,
            'allKegiatanBulananTambahan' => $allKegiatanBulananTambahan,
            'allKegiatanBulananPlt' => $allKegiatanBulananPlt,
        ]);
    }

    /**
     * Lists all KegiatanBulan models.
     * @return mixed
     */
    public function actionIndexBawahanV3()
    {
        \app\models\User::redirectDefaultPassword();

        $searchModel = new KegiatanBulananSearch();
        $searchModel->mode = 'atasan';
        $searchModel->id_kegiatan_tahunan_versi = 3;
        $dataProvider = $searchModel->searchBawahan(Yii::$app->request->queryParams);

        $query = $searchModel->getQueryBawahan(Yii::$app->request->queryParams);
        $query->andWhere(['kegiatan_tahunan.id_kegiatan_tahunan_jenis' => 1]);
        $query->andWhere(['instansi_pegawai.status_plt' => 0]);
        $query->orderBy(['kegiatan_tahunan.id_kegiatan_rhk' => SORT_ASC]);

        $allKegiatanBulanan = $query->all();

        $queryTambahan = $searchModel->getQueryBawahan(Yii::$app->request->queryParams);
        $queryTambahan->andWhere(['kegiatan_tahunan.id_kegiatan_tahunan_jenis' => 2]);
        $queryTambahan->andWhere(['instansi_pegawai.status_plt' => 0]);
        $queryTambahan->orderBy(['kegiatan_tahunan.id_kegiatan_rhk' => SORT_ASC]);

        $allKegiatanBulananTambahan = $queryTambahan->all();

        $queryPlt = $searchModel->getQueryBawahan(Yii::$app->request->queryParams);
        $queryPlt->andWhere(['instansi_pegawai.status_plt' => 1]);
        $queryPlt->orderBy(['kegiatan_tahunan.id_kegiatan_rhk' => SORT_ASC]);

        $allKegiatanBulananPlt = $queryPlt->all();

        if(Yii::$app->request->get('export')) {
            return $this->exportExcel(Yii::$app->request->queryParams);
        }

        return $this->render('index-v3', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'allKegiatanBulanan' => $allKegiatanBulanan,
            'allKegiatanBulananTambahan' => $allKegiatanBulananTambahan,
            'allKegiatanBulananPlt' => $allKegiatanBulananPlt,
        ]);
    }

    /**
     * Lists all KegiatanBulan models.
     * @return mixed
     */
    public function actionPegawaiIndex($bulan = null)
    {
        if ($bulan === null) {
            $bulan = date('m');
        }

        $searchModel = new KegiatanBulananSearch();
        $dataProvider = $searchModel->searchBySession(Yii::$app->request->queryParams, $bulan);

        return $this->render('pegawai/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'bulan' => $bulan
        ]);
    }

    /**
     * Lists all KegiatanBulan models.
     * @return mixed
     */
    public function actionSubordinatIndex($bulan = null)
    {
        if ($bulan === null) {
            $bulan = date('m');
        }
        $searchModel = new KegiatanBulananSearch();
        $dataProvider = $searchModel->searchSubordinat(Yii::$app->request->queryParams, $bulan);

        return $this->render('subordinat/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'bulan' => $bulan
        ]);
    }

    /**
     * Displays a single KegiatanBulan model.
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
     * Displays a single KegiatanBulan model.
     * @param integer $id
     * @return mixed
     */
    public function actionViewV2($id)
    {
        return $this->render('view-v2', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Displays a single KegiatanBulan model.
     * @param integer $id
     * @return mixed
     */
    public function actionViewV3($id)
    {
        return $this->render('view-v3', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionUpdateRealisasi($id)
    {
        $model = $this->findModel($id);

        $model->updateRealisasi();

        Yii::$app->session->setFlash('success','Realisasi berhasil diperbarui');

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Displays a single KegiatanBulan model.
     * @param integer $id
     * @return mixed
     */
    public function actionPegawaiView($id)
    {
        return $this->render('pegawai/view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Displays a single KegiatanBulan model.
     * @param integer $id
     * @return mixed
     */
    public function actionSubordinatView($id)
    {
        return $this->render('subordinat/view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new KegiatanBulan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id_kegiatan_tahunan = null)
    {
        $model = new KegiatanBulanan([
            'id_kegiatan_tahunan' => $id_kegiatan_tahunan
        ]);
        $model->loadDefaultAttributes();
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
     * Updates an existing KegiatanBulan model.
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
            if($model->save()) {
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

    public function actionEditableUpdate()
    {
         if (Yii::$app->request->post('hasEditable')) {

             if(Session::isPemeriksaKinerja()) {
                 $out = Json::encode(['output' => '', 'message' => 'Anda tidak memiliki akses']);
                 return $out;
             }

             $out = Json::encode(['output'=>'', 'message'=>'']);

             $id = Yii::$app->request->post('editableKey');
             $model = KegiatanBulanan::findOne($id);

             if ($model !== null) {
                 $posted = Yii::$app->request->post();
                 $post = ['KegiatanBulanan' => $posted];
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

    /**
     * Deletes an existing KegiatanBulan model.
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
     * Finds the KegiatanBulan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return KegiatanBulanan
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = KegiatanBulanan::findOne($id)) !== null) {
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

        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'Tahun');
        $sheet->setCellValue('C3', 'Kode Instansi');
        $sheet->setCellValue('D3', 'Kode Pegawai');
        $sheet->setCellValue('E3', 'Kode Kegiatan');
        $sheet->setCellValue('F3', 'Bulan');
        $sheet->setCellValue('G3', 'Target Kuantitas');

        $PHPExcel->getActiveSheet()->setCellValue('A1', 'Data KegiatanBulan');

        $PHPExcel->getActiveSheet()->mergeCells('A1:G1');

        $sheet->getStyle('A1:G3')->getFont()->setBold(true);
        $sheet->getStyle('A1:G3')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $row = 3;
        $i=1;

        $searchModel = new KegiatanBulananSearch();

        foreach($searchModel->querySearch($params)->all() as $data){
            $row++;
            $sheet->setCellValue('A' . $row, $i);
            $sheet->setCellValue('B' . $row, $data->tahun);
            $sheet->setCellValue('C' . $row, $data->kode_instansi);
            $sheet->setCellValue('D' . $row, $data->kode_pegawai);
            $sheet->setCellValue('E' . $row, $data->kode_kegiatan);
            $sheet->setCellValue('F' . $row, $data->bulan);
            $sheet->setCellValue('G' . $row, $data->target_kuantitas);

            $i++;
        }

        $sheet->getStyle('A3:G' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D3:G' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E3:G' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


        $sheet->getStyle('C' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('A3:G' . $row)->applyFromArray($setBorderArray);

        $path = 'exports/';
        $filename = time() . '_DataPenduduk.xlsx';
        $objWriter = \PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
        $objWriter->save($path.$filename);
        return Yii::$app->getResponse()->redirect($path.$filename);
    }

}
