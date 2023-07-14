<?php

namespace app\modules\absensi\controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Yii;
use app\modules\absensi\models\KetidakhadiranPanjang;
use app\modules\absensi\models\KetidakhadiranPanjangSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;
use app\modules\absensi\models\Absensi;
use function print_r;

/**
 * KetidakhadiranPanjangController implements the CRUD actions for KetidakhadiranPanjang model.
 */
class KetidakhadiranPanjangController extends Controller
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
                        'actions'=>['index'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function() { return $this->accessIndex(); }
                    ],
                    [
                        'actions'=>['create'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function() { return $this->accessCreate(); }
                    ],
                    [
                        'actions'=>['update'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function() { return $this->accessUpdate(); }
                    ],
                    [
                        'actions'=>['view'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function() { return $this->accessView(); }
                    ],
                    [
                        'actions'=>['delete'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function() { return $this->accessDelete(); }
                    ],
                    [
                        'actions'=>['set-setuju'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function() { return $this->accessSetSetuju(); }
                    ],
                    [
                        'actions'=>['set-tolak'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function() { return $this->accessSetTolak(); }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all KetidakhadiranPanjang models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new KetidakhadiranPanjangSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if(Yii::$app->request->get('export')) {

            if($searchModel->id_instansi == null
                AND $searchModel->bulan == null
                AND $searchModel->tanggal_mulai_awal == null
                AND $searchModel->tanggal_mulai_akhir == null
            ) {
                Yii::$app->session->setFlash('danger','Silahkan pilih salah satu filter pencarian');
                return $this->redirect(['/absensi/ketidakhadiran-panjang/index']);
            }

            return $this->exportExcel(Yii::$app->request->queryParams);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single KetidakhadiranPanjang model.
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
     * Creates a new KetidakhadiranPanjang model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($fromRekap = false)
    {
        $model = new KetidakhadiranPanjang();
        if ($fromRekap !== false && Yii::$app->request->isPost) {
            $model->id_pegawai = Yii::$app->request->post('rekap_id_pegawai');
            $model->tanggal_selesai = $model->tanggal_mulai = Yii::$app->request->post('rekap_tanggal');
        }

        $model->id_ketidakhadiran_panjang_status = 2;

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {

            $referrer = $_POST['referrer'];

            if($model->save()) {
                Yii::$app->session->setFlash('success','Data berhasil disimpan.');
                if ($fromRekap !== false) {
                    return $this->redirect(['/absensi/pegawai/view', 'id' => $model->id_pegawai]);
                } else {
                    return $this->redirect($referrer);
                }
            }

            Yii::$app->session->setFlash('error','Data gagal disimpan. Silahkan periksa kembali isian Anda.');

        }

        return $this->render('create', [
            'model' => $model,
            'referrer'=>$referrer
        ]);

    }

    /**
     * Updates an existing KetidakhadiranPanjang model.
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
     * Deletes an existing KetidakhadiranPanjang model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if($model->accessDelete()==false) {
            Yii::$app->session->setFlash('error','Anda tidak memiliki acccess');
            return $this->redirect(Yii::$app->request->referrer);
        }

        if($model->delete()) {
            Yii::$app->session->setFlash('success','Data berhasil dihapus');
        } else {
            Yii::$app->session->setFlash('error','Data gagal dihapus');
        }

        return $this->redirect(Yii::$app->request->referrer);

    }

    public function actionSetSetuju($id)
    {
        $model = $this->findModel($id);
        $model->id_ketidakhadiran_panjang_status = Absensi::KETIDAKHADIRAN_SETUJU;
        $model->save();
        if ($model->save() == false) {
            print_r($model->errors);die;
        }

        Yii::$app->session->setFlash('success','Ketidakhadiran berhasil disetjui');
        $referrer = Yii::$app->request->referrer;

        return $this->redirect($referrer);

    }

    public function actionSetTolak($id)
    {
        $model = $this->findModel($id);
        $model->id_ketidakhadiran_panjang_status = Absensi::KETIDAKHADIRAN_TOLAK;
        $model->save();

        $referrer = Yii::$app->request->referrer;

        return $this->redirect($referrer);

    }

    public function accessIndex()
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

        if(User::isAdminInstansi()) {
            return true;
        }

        if (User::isOperatorAbsen()) {
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

        if(User::isInstansi()) {
            return true;
        }

        if(User::isAdminInstansi()) {
            return true;
        }

        if (User::isOperatorAbsen()) {
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

        if(User::isAdminInstansi()) {
            return true;
        }

        if (User::isOperatorAbsen()) {
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

        if(User::isAdminInstansi()) {
            return true;
        }

        if (User::isOperatorAbsen()) {
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

        if(User::isAdminInstansi()) {
            return true;
        }

        if (User::isOperatorAbsen()) {
            return true;
        }

        return false;
    }

    public function accessSetSetuju()
    {
        if(User::isAdmin()) {
            return true;
        }

        if(User::isVerifikator()) {
            return true;
        }

        if(User::isAdminInstansi()) {
            return true;
        }

        return false;
    }

    public function accessSetTolak()
    {
        if(User::isAdmin()) {
            return true;
        }

        if(User::isVerifikator()) {
            return true;
        }

        if(User::isAdminInstansi()) {
            return true;
        }

        return false;
    }

    /**
     * Finds the KetidakhadiranPanjang model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return KetidakhadiranPanjang the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = KetidakhadiranPanjang::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function exportExcel($params)
    {
        $spreadsheet = new Spreadsheet();

        $spreadsheet->setActiveSheetIndex(0);

        $spreadsheet->getDefaultStyle()->getAlignment()->setWrapText(true);
        $spreadsheet->getDefaultStyle()->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $sheet = $spreadsheet->getActiveSheet();

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
        $sheet->setCellValue('B3', 'Pegawai');
        $sheet->setCellValue('C3', 'Jenis');
        $sheet->setCellValue('D3', 'Tanggal Mulai');
        $sheet->setCellValue('E3', 'Tanggal Selesai');
        $sheet->setCellValue('F3', 'Status');

        $sheet->setCellValue('A1', 'Data KetidakhadiranPanjang');

        $sheet->mergeCells('A1:F1');

        $sheet->getStyle('A1:F3')->getFont()->setBold(true);
        $sheet->getStyle('A1:F3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $row = 3;
        $i=1;

        $searchModel = new KetidakhadiranPanjangSearch();

        foreach($searchModel->getQuerySearch($params)->all() as $data){
            $row++;
            $sheet->setCellValue('A' . $row, $i);
            $sheet->setCellValue('B' . $row, @$data->pegawai->nama);
            $sheet->setCellValue('C' . $row, @$data->ketidakhadiranPanjangJenis->nama);
            $sheet->setCellValue('D' . $row, $data->tanggal_mulai);
            $sheet->setCellValue('E' . $row, $data->tanggal_selesai);
            $sheet->setCellValue('F' . $row, @$data->ketidakhadiranPanjangStatus->nama);

            $i++;
        }

        $sheet->getStyle('A3:F' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D3:F' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E3:F' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('C' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('A3:F' . $row)->applyFromArray($setBorderArray);

        $path = 'exports/';
        $filename = 'Ketidakhadiran Kerja - '.date('YmdHis').'.xlsx';
        //$objWriter = \PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
        //$objWriter->save($path.$filename);
        $writer = new Xlsx($spreadsheet);
        $writer->save($path.$filename);

        return Yii::$app->getResponse()->redirect($path.$filename);
    }

}
