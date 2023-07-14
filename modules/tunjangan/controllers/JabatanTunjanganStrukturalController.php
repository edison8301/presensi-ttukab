<?php

namespace app\modules\tunjangan\controllers;

use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Yii;
use app\components\Helper;
use app\models\User;
use app\modules\tunjangan\models\JabatanTunjanganStruktural;
use app\modules\tunjangan\models\JabatanTunjanganStrukturalSearch;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * JabatanTunjanganStrukturalController implements the CRUD actions for JabatanTunjanganStruktural model.
 */
class JabatanTunjanganStrukturalController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete','editable-update', 'perawatan'],
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
     * Lists all JabatanTunjanganStruktural models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new JabatanTunjanganStrukturalSearch();
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
     * Displays a single JabatanTunjanganStruktural model.
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
     * Creates a new JabatanTunjanganStruktural model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new JabatanTunjanganStruktural();

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
     * Updates an existing JabatanTunjanganStruktural model.
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
     * Deletes an existing JabatanTunjanganStruktural model.
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
     * Finds the JabatanTunjanganStruktural model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return JabatanTunjanganStruktural the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = JabatanTunjanganStruktural::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function exportExcel($params)
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


        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);

        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'Id Instansi');
        $sheet->setCellValue('C3', 'Id Eselon');
        $sheet->setCellValue('D3', 'Besaran Tpp');

        $spreadsheet->getActiveSheet()->setCellValue('A1', 'Data JabatanTunjanganStruktural');

        $spreadsheet->getActiveSheet()->mergeCells('A1:D1');

        $sheet->getStyle('A1:D3')->getFont()->setBold(true);
        $sheet->getStyle('A1:D3')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $row = 3;
        $i=1;

        $searchModel = new JabatanTunjanganStrukturalSearch();

        foreach($searchModel->getQuerySearch($params)->all() as $data){
            $row++;
            $sheet->setCellValue('A' . $row, $i);
            $sheet->setCellValue('B' . @$row, @$data->instansi->nama);
            $sheet->setCellValue('C' . @$row, @$data->eselon->nama);
            $sheet->setCellValueExplicit('D' . $row, $data->besaran_tpp, DataType::TYPE_STRING);

            $i++;
        }

        $sheet->getStyle('A3:D' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D3:D' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E3:D' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


        $sheet->getStyle('C' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('A3:D' . $row)->applyFromArray($setBorderArray);

        $path = Yii::getAlias('@app/files/');
        $filename = time() . '_DataJabatanTunjanganStruktural.xlsx';

        ob_end_clean();
        $writer = new Xlsx($spreadsheet);
        $writer->save($path . $filename);
        return Yii::$app->response->sendFile($path.$filename);
    }

    public function actionEditableUpdate($jenis = null)
    {
        if (Yii::$app->request->post('hasEditable')) {

            $id = Yii::$app->request->post('editableKey');
            $model = $this->findModel($id);

            $posted = Yii::$app->request->post();
            $post = ['JabatanTunjanganStruktural' => $posted];

            $output = '';

            if ($model->load($post)) {
                if ($model->save()){

                if ($jenis == 'eselon') {
                    $output = @$model->eselon->nama;
                }
                if ($jenis == 'golongan') {
                    $output = @$model->golongan->nama;
                }
                if ($jenis == 'besaran_tpp') {
                    $output = Helper::rp($model->besaran_tpp);
                }

                $out = Json::encode(['output'=> $output, 'message'=>'']);
            }
            else
                $out = Json::encode(['output'=>$output, 'message'=>'Internal Server Error']);
            } else {
                $out = Json::encode(['output'=>'gagal', 'message'=>'']);
            }

            return $out;
        }
    }

    public function actionPerawatan()
    {
        $query = JabatanTunjanganStruktural::find();
        $query->andWhere('tanggal_mulai < :tanggal', [
            ':tanggal' => '2022-01-01',
        ]);

        foreach ($query->all() as $data) {
            $data->updateAttributes([
                'tanggal_selesai' => '2021-12-31'
            ]);
        }
    }

}
