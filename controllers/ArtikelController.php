<?php

namespace app\controllers;

use Yii;
use app\models\Artikel;
use app\models\ArtikelSearch;
use app\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * ArtikelController implements the CRUD actions for Artikel model.
 */
class ArtikelController extends Controller
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
     * Lists all Artikel models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ArtikelSearch();
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
     * Displays a single Artikel model.
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
     * Creates a new Artikel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->enableCsrfValidation = false;
        $model = new Artikel();

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {
            $post['Artikel'] = Yii::$app->request->post();
            $referrer = $_POST['referrer'];

            $model->load($post);
            $model->thumbnail = null;
            $model->id_user_buat = User::getIdUser();

            $datetime = \DateTime::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'));
            $foto = UploadedFile::getInstance($model, 'thumbnail');

            if($foto != null) {
                $thumbnail = 'cover_'.$datetime->format('YmdHis').'.'.$foto->extension;
                $model->thumbnail = $thumbnail;
            }

            if($model->save()) {
                $path = Yii::getAlias('@app').'/web/uploads/artikel/';
                if($foto != null) {
                    $foto->saveAs($path.$model->thumbnail, false);
                }

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
     * Updates an existing Artikel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
    $this->enableCsrfValidation = false;
    $model = Artikel::findOne($id);

    $fotoLama = $model->thumbnail;

    $referrer = Yii::$app->request->referrer;

    if ($model->load(Yii::$app->request->post())) {
        $post['Artikel'] = Yii::$app->request->post();
        $referrer = $_POST['referrer'];

        $model->load($post);
        $model->thumbnail = $fotoLama;

        $datetime = \DateTime::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'));
        $foto = UploadedFile::getInstance($model, 'thumbnail');

        if($foto != null) {
            $thumbnail = 'cover_'.$datetime->format('YmdHis').'.'.$foto->extension;
            $model->thumbnail = $thumbnail;
        }

        if($model->save()) {
            $path = Yii::getAlias('@app').'/web/uploads/artikel/';
            if(file_exists($path.$fotoLama) != false and $foto != null) {
                unlink($path.$fotoLama);
            }

            if($foto != null){
                $foto->saveAs($path.$model->thumbnail, false);
            }

            Yii::$app->session->setFlash('success','Data berhasil diubah.');
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
     * Deletes an existing Artikel model.
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
     * Finds the Artikel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Artikel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Artikel::findOne($id)) !== null) {
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
        $sheet->setCellValue('B3', 'Judul');
        $sheet->setCellValue('C3', 'Slug');
        $sheet->setCellValue('D3', 'Konten');
        $sheet->setCellValue('E3', 'Id User Buat');
        $sheet->setCellValue('F3', 'Id User Ubah');
        $sheet->setCellValue('G3', 'Id Artikel Kategori');
        $sheet->setCellValue('H3', 'Waktu Buat');
        $sheet->setCellValue('I3', 'Waktu Ubah');
        $sheet->setCellValue('J3', 'Waktu Terbit');
        $sheet->setCellValue('K3', 'Thumbnail');

        $PHPExcel->getActiveSheet()->setCellValue('A1', 'Data Artikel');

        $PHPExcel->getActiveSheet()->mergeCells('A1:K1');

        $sheet->getStyle('A1:K3')->getFont()->setBold(true);
        $sheet->getStyle('A1:K3')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $row = 3;
        $i=1;

        $searchModel = new ArtikelSearch();

        foreach($searchModel->getQuerySearch($params)->all() as $data){
            $row++;
            $sheet->setCellValue('A' . $row, $i);
            $sheet->setCellValue('B' . $row, $data->judul);
            $sheet->setCellValue('C' . $row, $data->slug);
            $sheet->setCellValue('D' . $row, $data->konten);
            $sheet->setCellValue('E' . $row, $data->id_user_buat);
            $sheet->setCellValue('F' . $row, $data->id_user_ubah);
            $sheet->setCellValue('G' . $row, $data->id_artikel_kategori);
            $sheet->setCellValue('H' . $row, $data->waktu_buat);
            $sheet->setCellValue('I' . $row, $data->waktu_ubah);
            $sheet->setCellValue('J' . $row, $data->waktu_terbit);
            $sheet->setCellValue('K' . $row, $data->thumbnail);

            $i++;
        }

        $sheet->getStyle('A3:K' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D3:K' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E3:K' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


        $sheet->getStyle('C' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('A3:K' . $row)->applyFromArray($setBorderArray);

        $path = Yii::getAlias('@app/exports/');
        $filename = time() . '_DataPenduduk.xlsx';

        ob_end_clean();
        $objWriter = \PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
        $objWriter->save($path.$filename);
        return Yii::$app->response->sendFile($path.$filename);
    }

}
