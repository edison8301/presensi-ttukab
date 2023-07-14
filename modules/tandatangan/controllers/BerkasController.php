<?php

namespace app\modules\tandatangan\controllers;

use Yii;
use app\modules\tandatangan\models\Berkas;
use app\modules\tandatangan\models\BerkasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\User;
use app\modules\tandatangan\models\RiwayatJenis;
use app\modules\tandatangan\models\Verifikasi;
use yii\httpclient\Client;
use yii\web\ForbiddenHttpException;

/**
 * BerkasController implements the CRUD actions for Berkas model.
 */
class BerkasController extends Controller
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
                        'actions' => ['view', 'create', 'update'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return User::isAdmin() || User::isPegawai();
                        }
                    ],
                    [
                        'actions' => ['index', 'delete', 'get-berkas', 'nik'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return User::isAdmin() || User::isPegawai() OR User::isInstansi();
                        }
                    ]
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
     * Lists all Berkas models.
     * @return mixed
     */
    public function actionIndex($id_berkas_status=null)
    {
        User::redirectDefaultPassword();
        clearstatcache();

        $searchModel = new BerkasSearch();

        if($id_berkas_status !== null) {
            $searchModel->id_berkas_status = $id_berkas_status;
        }

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
     * Displays a single Berkas model.
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
     * Creates a new Berkas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Berkas();

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
     * Updates an existing Berkas model.
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
     * Deletes an existing Berkas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if(!$model->accessDelete()) {
            throw new ForbiddenHttpException('Anda tidak diperbolehkan untuk melakukan aksi ini');
        }

        $berkas_mentah = $model->berkas_mentah;
        $berkas_mentah_tandatangan = $model->berkas_mentah_tandatangan;
        $berkas_tandatangan = $model->berkas_tandatangan;

        if($model->delete()) {
            $query = Verifikasi::find();
            $query->andWhere(['id_berkas' => $id]);

            foreach($query->all() as $verifikasi) {
                $verifikasi->delete();
            }

            $this->deleteFileBerkas('berkas-mentah', $berkas_mentah);
            $this->deleteFileBerkas('berkas-mentah-tandatangan', $berkas_mentah_tandatangan);
            $this->deleteFileBerkas('berkas-tandatangan', $berkas_tandatangan);

            Yii::$app->session->setFlash('success','Data berhasil dihapus');
        } else {
            Yii::$app->session->setFlash('error','Data gagal dihapus');
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    private function deleteFileBerkas($dir, $filename)
    {
        $url = @Yii::$app->params['url_tandatangan'];
        $params = '/api/berkas/delete-file/'.$dir.'/'.$filename;

        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl($url.$params)
            ->send();

        return true;
    }

    public function actionGetBerkas($id, $dir, $filename)
    {
        $model = $this->findModel($id);

        if($dir == 'berkas-mentah') {
            $model->generateRiwayat(RiwayatJenis::BERKAS_MENTAH_DIUNDUH);
        }

        if($dir == 'berkas-tandatangan') {
            $model->generateRiwayat(RiwayatJenis::BERKAS_TANDATANGAN_DIUNDUH);
        }

        $url = @Yii::$app->params['url_tandatangan'];
        $params = "/$dir/$filename";

        return $this->redirect($url.$params);
    }

    /**
     * Finds the Berkas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Berkas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Berkas::findOne($id)) !== null) {
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
        $sheet->getColumnDimension('L')->setWidth(20);

        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'Nama');
        $sheet->setCellValue('C3', 'Uraian');
        $sheet->setCellValue('D3', 'Berkas Mentah');
        $sheet->setCellValue('E3', 'Berkas Tandatangan');
        $sheet->setCellValue('F3', 'Id Berkas Status');
        $sheet->setCellValue('G3', 'Nip Tandatangan');
        $sheet->setCellValue('H3', 'Waktu Tandatangan');
        $sheet->setCellValue('I3', 'Id Aplikasi');
        $sheet->setCellValue('J3', 'Created At');
        $sheet->setCellValue('K3', 'Updated At');
        $sheet->setCellValue('L3', 'Deleted At');

        $PHPExcel->getActiveSheet()->setCellValue('A1', 'Data Berkas');

        $PHPExcel->getActiveSheet()->mergeCells('A1:L1');

        $sheet->getStyle('A1:L3')->getFont()->setBold(true);
        $sheet->getStyle('A1:L3')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $row = 3;
        $i=1;

        $searchModel = new BerkasSearch();

        foreach($searchModel->getQuerySearch($params)->all() as $data){
            $row++;
            $sheet->setCellValue('A' . $row, $i);
            $sheet->setCellValue('B' . $row, $data->nama);
            $sheet->setCellValue('C' . $row, $data->uraian);
            $sheet->setCellValue('D' . $row, $data->berkas_mentah);
            $sheet->setCellValue('E' . $row, $data->berkas_tandatangan);
            $sheet->setCellValue('F' . $row, $data->id_berkas_status);
            $sheet->setCellValue('G' . $row, $data->nip_tandatangan);
            $sheet->setCellValue('H' . $row, $data->waktu_tandatangan);
            $sheet->setCellValue('I' . $row, $data->id_aplikasi);
            $sheet->setCellValue('J' . $row, $data->created_at);
            $sheet->setCellValue('K' . $row, $data->updated_at);
            $sheet->setCellValue('L' . $row, $data->deleted_at);

            $i++;
        }

        $sheet->getStyle('A3:L' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D3:L' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E3:L' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


        $sheet->getStyle('C' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('A3:L' . $row)->applyFromArray($setBorderArray);

        $path = 'exports/';
        $filename = time() . '_DataPenduduk.xlsx';
        $objWriter = \PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
        $objWriter->save($path.$filename);
        return Yii::$app->getResponse()->redirect($path.$filename);
    }

    public function actionNik($nip) {
        $url = 'http://localhost:8074/simadig-laravel/public/api/pegawai/view/'.$nip;

        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl($url)
            ->send();

        $responseJson = json_decode($response->content);

        print_r($response->content);die;

        return $responseJson;
    }

}
