<?php

namespace app\controllers;

use app\components\Session;
use app\models\Instansi;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Yii;
use app\models\Peta;
use app\models\PetaSearch;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * PetaController implements the CRUD actions for Peta model.
 */
class PetaController extends Controller
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
                            'index', 'view', 'update',
                            'set-peta-point', 'import-sd', 'import-smp',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['create', 'delete', 'perawatan'],
                        'allow' => Session::isAdmin(),
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['set-kunci', 'set-buka'],
                        'allow' => Session::isAdmin(),
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
     * Lists all Peta models.
     * @return mixed
     */
    public function actionIndex($mode='instansi')
    {
        $searchModel = new PetaSearch();
        $searchModel->mode = $mode;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if(Yii::$app->request->get('export')) {
            return $this->exportExcel(Yii::$app->request->queryParams, $mode);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Peta model.
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
     * Creates a new Peta model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($mode='instansi')
    {
        $model = new Peta();
        $model->jarak = 50;

        if ($mode == 'pegawai') {
            $model->status_rumah = 0;
        }

        if ($mode == 'pegawai-wfh') {
            $model->status_rumah = 1;
        }

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {

            $referrer = $_POST['referrer'];

            $latlong = $model->latlong;
            if($latlong != null){
                $break = explode(',', $latlong);

                $model->latitude = @$break[0];
                $model->longitude = @$break[1];
            }

            if($model->save()) {
                Yii::$app->session->setFlash('success','Data berhasil disimpan.');
                return $this->redirect($referrer);
            }

            Yii::$app->session->setFlash('error','Data gagal disimpan. Silahkan periksa kembali isian Anda.');

        }

        return $this->render('create', [
            'model' => $model,
            'referrer'=>$referrer,
            'mode' => $mode,
        ]);

    }

    /**
     * Updates an existing Peta model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $mode = Yii::$app->request->get('mode');

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {

            $referrer = $_POST['referrer'];

            $latlong = $model->latlong;

            if($latlong != null){
                $break = explode(',', $latlong);

                $model->latitude = @$break[0];
                $model->longitude = @$break[1];
            }

            if($model->save())
            {
                Yii::$app->session->setFlash('success','Data berhasil disimpan.');
                return $this->redirect($referrer);
            }

            Yii::$app->session->setFlash('error','Data gagal disimpan. Silahkan periksa kembali isian Anda.');


        }

        return $this->render('update', [
            'model' => $model,
            'referrer'=>$referrer,
            'mode' => $mode,
        ]);

    }

    /**
     * Deletes an existing Peta model.
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
     * Finds the Peta model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Peta the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Peta::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function exportExcel($params, $mode='instansi')
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


        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(40);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(8);

        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'Nama');
        $sheet->setCellValue('C3', $mode == 'instansi' ? 'Perangkat Daerah' : 'NIP');
        $sheet->setCellValue('D3', 'Latitude');
        $sheet->setCellValue('E3', 'Longitude');
        $sheet->setCellValue('F3', 'Jarak');

        $title = 'Data Peta Perangkat Daerah';

        if ($mode == 'pegawai') {
            $title = 'Data Peta Pegawai';
        }

        if ($mode == 'pegawai-wfh') {
            $title = 'Data Peta Pegawai WFH';
        }

        $sheet->setCellValue('A1', $title);

        $sheet->mergeCells('A1:F1');

        $sheet->getStyle('A1:F3')->getFont()->setBold(true);
        $sheet->getStyle('A1:F3')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $row = 3;
        $i=1;

        $searchModel = new PetaSearch();
        $searchModel->mode = $mode;

        $query = $searchModel->getQuerySearch($params);

        foreach($query->all() as $data){
            $row++;
            $sheet->setCellValue('A' . $row, $i);
            $sheet->setCellValue('B' . $row, $data->nama);

            if ($mode == 'instansi') {
                $sheet->setCellValue('C' . $row, @$data->instansi->nama);
            }

            if ($mode != 'instansi') {
                $sheet->setCellValueExplicit('C' . $row, @$data->pegawai->nip, DataType::TYPE_STRING);
            }

            $sheet->setCellValue('D' . $row, $data->latitude);
            $sheet->setCellValue('E' . $row, $data->longitude);
            $sheet->setCellValue('F' . $row, $data->jarak);

            $i++;
        }

        $sheet->getStyle('A3:F' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B4:C' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        $sheet->getStyle('A3:F' . $row)->applyFromArray($setBorderArray);

        $path = Yii::getAlias('@app/files/');
        $filename = time() . '_DataPeta.xlsx';

        ob_end_clean();
        $writer = new Xlsx($spreadsheet);
        $writer->save($path . $filename);
        return Yii::$app->response->sendFile($path.$filename);
    }

    public function actionSetPetaPoint($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isPost) {
            $koordinat = Json::encode(Yii::$app->request->post('koordinat'));

            echo $model->setKoordinat($koordinat);

        } else {
            return Json::encode([
                'status' => 'error',
                'message' => 'Post dibutuhkan!'
            ]);
        }
    }

    public function actionImportSd()
    {
        $model = new Peta();

        if ($model->load(Yii::$app->request->post())) {

            $array_line = explode("\n", $model->keterangan);
            foreach ($array_line as $line) {
                $data = explode("\t",$line);

                $peta = new Peta();

                $peta->nama = $data[1];
                $peta->id_instansi = Instansi::findOrCreateIdInstansiSd($data[1], $data[2], $data[3]);
                $peta->id_peta_jenis = 1;
                $peta->latlong = $data[2].', '.$data[3];
                $peta->latitude = $data[2];
                $peta->longitude = $data[3];
                $peta->jarak = 50;
                $peta->keterangan = '';

                $peta->save(false);
            }
        }

        return $this->render('import-sd',[
            'model' => $model
        ]);
    }

    public function actionImportSmp()
    {
        $model = new Peta();

        if ($model->load(Yii::$app->request->post())) {

            $array_line = explode("\n", $model->keterangan);
            foreach ($array_line as $line) {
                $data = explode("\t",$line);

                $peta = new Peta();

                $peta->nama = $data[1];
                $peta->id_instansi = Instansi::findOrCreateIdInstansiSmp($data[1], $data[2], $data[3]);
                $peta->id_peta_jenis = 1;
                $peta->latlong = $data[2].', '.$data[3];
                $peta->latitude = $data[2];
                $peta->longitude = $data[3];
                $peta->jarak = 50;
                $peta->keterangan = '';

                $peta->save(false);
            }
        }

        return $this->render('import-smp',[
            'model' => $model
        ]);
    }

    public function actionSetBuka($id)
    {
        $model = $this->findModel($id);
        $model->status_kunci = Peta::BUKA;
        $model->save();

        Yii::$app->session->setFlash('success','Data berhasil dibuka');
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionSetKunci($id)
    {
        $model = $this->findModel($id);
        $model->status_kunci = Peta::KUNCI;
        $model->save();

        Yii::$app->session->setFlash('success','Data berhasil dikunci');
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionPerawatan()
    {
        Peta::deleteAll('status_rumah = 1 AND latitude IS NULL');
        Peta::deleteAll('status_rumah = 1 AND latitude = "null"');
    }

}
