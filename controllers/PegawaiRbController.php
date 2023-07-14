<?php

namespace app\controllers;

use app\components\Helper;
use app\components\Session;
use app\models\Pegawai;
use app\models\PegawaiRbJenis;
use Yii;
use app\models\PegawaiRb;
use app\models\PegawaiRbSearch;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\helpers\Json;
use yii\httpclient\Client;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\User;

/**
 * PegawaiRbController implements the CRUD actions for PegawaiRb model.
 */
class PegawaiRbController extends Controller
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
                            'refresh', 'editable-update',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return User::isAdmin() OR User::isInstansi();
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
     * Lists all PegawaiRb models.
     * @return mixed
     */
    public function actionIndex($id_pegawai_rb_jenis=null)
    {
        $searchModel = new PegawaiRbSearch();
        $searchModel->id_pegawai_rb_jenis = $id_pegawai_rb_jenis;
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
     * Displays a single PegawaiRb model.
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
     * Creates a new PegawaiRb model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id_pegawai_rb_jenis=null)
    {
        $model = new PegawaiRb();
        $model->tahun = Session::getTahun();
        $model->status_realisasi = 1;
        $model->id_pegawai_rb_jenis = $id_pegawai_rb_jenis;

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
     * Updates an existing PegawaiRb model.
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

    public function actionEditableUpdate()
    {
        if (Yii::$app->request->post('hasEditable')) {

            $id = Yii::$app->request->post('editableKey');
            $model = $this->findModel($id);

            $posted = Yii::$app->request->post();
            $post = ['PegawaiRb' => $posted];

            $out = Json::encode(['output'=>'', 'message'=>'']);

            if ($model->load($post)) {
                $output = Helper::getTanggal($model->tanggal);

                if ($model->save()) {
                    $out = Json::encode(['output' => $output, 'message' => '']);
                } else {
                    $out = Json::encode(['output'=>'', 'message'=>'error']);
                }
            }

            return $out;
        }
    }

    public function getUrlRb($id_pegawai_rb_jenis)
    {
        $tahun = Session::getTahun();
        $url = @Yii::$app->params['url_simdiklat'];

        if ($id_pegawai_rb_jenis == PegawaiRbJenis::PEMUTAKHIRAN_SIMADIG) {
            $url = @Yii::$app->params['url_simadig'];
            $url .= '/api/pegawai/index-status-pemutakhiran-data-mandiri';
        }

        if ($id_pegawai_rb_jenis == PegawaiRbJenis::PERENCANAAN_BANGKOM) {
            $url .= '/index.php?r=api/bangkom/pegawai/index-status-pengisian-rencana&tahun=' . $tahun;
        }

        if ($id_pegawai_rb_jenis == PegawaiRbJenis::REALISASI_BANGKOM) {
            $url .= '/index.php?r=api/bangkom/pegawai/index-status-realisasi-20-jam&tahun=' . $tahun;
        }

        return $url;
    }

    public function actionRefresh($id_pegawai_rb_jenis)
    {
        if ($id_pegawai_rb_jenis == PegawaiRbJenis::PEMUTAKHIRAN_SIMADIG) {
            if (Session::getTahun() != '2022') {
                Yii::$app->session->setFlash('danger', 'Gagal direfresh');
                return $this->redirect(Yii::$app->request->referrer);
            }

            return $this->refreshPemutakhiranSimadig($id_pegawai_rb_jenis);
        }

        $url = $this->getUrlRb($id_pegawai_rb_jenis);

        try {
            $client = new Client();
            $response = $client->createRequest()
                ->setMethod('GET')
                ->setUrl($url)
                ->send();

            $responseJson = json_decode($response->content);

            $allData = $responseJson->data;

            foreach ($allData as $data) {
                if ($data->status == 0 OR $data->status == false) {
                    continue;
                }

                $model = PegawaiRb::findOrCreate([
                    'id_pegawai' => $data->id,
                    'id_pegawai_rb_jenis' => $id_pegawai_rb_jenis,
                ]);

                $model->updateAttributes([
                    'status_realisasi' => true,
                ]);
            }

            Yii::$app->session->setFlash('success', 'Berhasil direfresh');
            return $this->redirect(Yii::$app->request->referrer);
        } catch (\yii\httpclient\Exception $e) {
            Yii::$app->session->setFlash('danger', 'Gagal direfresh');
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    public function refreshPemutakhiranSimadig($id_pegawai_rb_jenis)
    {
        $url = $this->getUrlRb($id_pegawai_rb_jenis);

        try {
            $client = new Client();
            $response = $client->createRequest()
                ->setMethod('GET')
                ->setUrl($url)
                ->send();

            $responseJson = json_decode($response->content);

            $allData = $responseJson->data;

            foreach ($allData as $data) {

                $pegawai = Pegawai::findOne([
                    'nip' => $data->nip,
                ]);

                if ($pegawai == null) {
                    continue;
                }

                $model = PegawaiRb::findOrCreate([
                    'id_pegawai' => $pegawai->id,
                    'id_pegawai_rb_jenis' => $id_pegawai_rb_jenis,
                ]);

                $model->updateAttributes([
                    'status_realisasi' => true,
                ]);
            }

            Yii::$app->session->setFlash('success', 'Berhasil direfresh');
            return $this->redirect(Yii::$app->request->referrer);
        } catch (\yii\httpclient\Exception $e) {
            Yii::$app->session->setFlash('danger', 'Gagal direfresh');
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    /**
     * Deletes an existing PegawaiRb model.
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
     * Finds the PegawaiRb model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PegawaiRb the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PegawaiRb::findOne($id)) !== null) {
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
        $sheet->setCellValue('C3', 'Tanggal');
        $sheet->setCellValue('D3', 'Id Pegawai');
        $sheet->setCellValue('E3', 'Id Pegawai Rb Jenis');
        $sheet->setCellValue('F3', 'Status Realisasi');

        $PHPExcel->getActiveSheet()->setCellValue('A1', 'Data PegawaiRb');

        $PHPExcel->getActiveSheet()->mergeCells('A1:F1');

        $sheet->getStyle('A1:F3')->getFont()->setBold(true);
        $sheet->getStyle('A1:F3')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $row = 3;
        $i=1;

        $searchModel = new PegawaiRbSearch();

        foreach($searchModel->getQuerySearch($params)->all() as $data){
            $row++;
            $sheet->setCellValue('A' . $row, $i);
            $sheet->setCellValue('B' . $row, $data->tahun);
            $sheet->setCellValue('C' . $row, $data->tanggal);
            $sheet->setCellValue('D' . $row, $data->id_pegawai);
            $sheet->setCellValue('E' . $row, $data->id_pegawai_rb_jenis);
            $sheet->setCellValue('F' . $row, $data->status_realisasi);

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
