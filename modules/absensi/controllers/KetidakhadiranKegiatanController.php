<?php

namespace app\modules\absensi\controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Yii;
use app\modules\absensi\models\KetidakhadiranKegiatan;
use app\modules\absensi\models\KetidakhadiranKegiatanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;

/**
 * KetidakhadiranKegiatanController implements the CRUD actions for KetidakhadiranKegiatan model.
 */
class KetidakhadiranKegiatanController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions'=>['index'],
                        'allow' => true,
                        'matchCallback' => function() { return $this->accessIndex(); }
                    ],
                    [
                        'actions'=>['create'],
                        'allow' => true,
                        'matchCallback' => function() { return $this->accessCreate(); }
                    ],
                    [
                        'actions'=>['update'],
                        'allow' => true,
                        'matchCallback' => function() { return $this->accessUpdate(); }
                    ],
                    [
                        'actions'=>['view'],
                        'allow' => true,
                        'matchCallback' => function() { return $this->accessView(); }
                    ],
                    [
                        'actions'=>['delete'],
                        'allow' => true,
                        'matchCallback' => function() { return $this->accessDelete(); }
                    ],
                    [
                        'actions'=>['set-setuju'],
                        'allow' => true,
                        'matchCallback' => function() { return $this->accessSetSetuju(); }
                    ],
                    [
                        'actions'=>['set-tolak'],
                        'allow' => true,
                        'matchCallback' => function() { return $this->accessSetTolak(); }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all KetidakhadiranKegiatan models.
     * @return mixed
     */
    public function actionIndex($id_ketidakhadiran_kegiatan_jenis=null)
    {
        $searchModel = new KetidakhadiranKegiatanSearch();
        $searchModel->id_ketidakhadiran_kegiatan_jenis = $id_ketidakhadiran_kegiatan_jenis;

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort->defaultOrder = ['tanggal' => SORT_DESC];

        if(Yii::$app->request->get('export')) {
            $query = $searchModel->getQuerySearch(Yii::$app->request->queryParams);
            return $this->exportExcel($query);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'id_ketidakhadiran_kegiatan_jenis'=>$id_ketidakhadiran_kegiatan_jenis
        ]);
    }

    /**
     * Displays a single KetidakhadiranKegiatan model.
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
     * Creates a new KetidakhadiranKegiatan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id_ketidakhadiran_kegiatan_jenis,$id_pegawai=null)
    {
        if(KetidakhadiranKegiatan::accessCreate(['id_ketidakhadiran_kegiatan_jenis'=>$id_ketidakhadiran_kegiatan_jenis])==false) {
            throw new \yii\web\HttpException(403, 'Anda tidak memiliki akses terhadap halaman ini');
        }

        $model = new KetidakhadiranKegiatan();

        $model->id_ketidakhadiran_kegiatan_jenis = $id_ketidakhadiran_kegiatan_jenis;
        $model->id_pegawai = $id_pegawai;

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {

            $referrer = $_POST['referrer'];

            if($model->isMasihPunyaJatah()==false) {
                Yii::$app->session->setFlash('danger','Pegawai sudah melewati batas izin kegiatan sebanyak 4 kali dalam 1 bulan');
                return $this->redirect(['create',
                    'id_ketidakhadiran_kegiatan_jenis'=>$id_ketidakhadiran_kegiatan_jenis,
                    'id_pegawai'=>$id_pegawai
                ]);
            }

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
     * Updates an existing KetidakhadiranKegiatan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if($model->accessUpdate() == false) {
            throw new \yii\web\HttpException(403, 'Anda tidak memiliki akses terhadap halaman ini');
        }

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {

            $referrer = $_POST['referrer'];

            if($model->isMasihPunyaJatah()==false AND $model->id_ketidakhadiran_kegiatan_status==1) {
                Yii::$app->session->setFlash('danger','Pegawai sudah melewati batas izin kegiatan sebanyak 4 kali dalam 1 bulan');
                return $this->redirect(['update','id'=>$id]);
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
            'referrer'=>$referrer
        ]);

    }

    /**
     * Deletes an existing KetidakhadiranKegiatan model.
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
     * Finds the KetidakhadiranKegiatan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return KetidakhadiranKegiatan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = KetidakhadiranKegiatan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
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

        if(User::isAdminInstansi()) {
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

    public function exportExcel($query)
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
        $sheet->getColumnDimension('F')->setWidth(40);

        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'Pegawai');
        $sheet->setCellValue('C3', 'Tanggal');
        $sheet->setCellValue('D3', 'Jenis Ketidakhadiran Kegiatan');
        $sheet->setCellValue('E3', 'Jenis Keterangan');
        $sheet->setCellValue('F3', 'Keterangan');

        $sheet->setCellValue('A1', 'Data KetidakhadiranKegiatan');

        $sheet->mergeCells('A1:F1');

        $sheet->getStyle('A1:F3')->getFont()->setBold(true);
        $sheet->getStyle('A1:F3')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $row = 3;
        $i=1;

        $searchModel = new KetidakhadiranKegiatanSearch();

        /* @var $listPegawai \app\models\Pegawai[] */
        $listPegawai = $query->all();

        foreach($listPegawai as $data)
        {
            $row++;
            $sheet->setCellValue('A' . $row, $i);
            $sheet->setCellValue('B' . $row, @$data->pegawai->nama);
            $sheet->setCellValue('C' . $row, $data->tanggal);
            $sheet->setCellValue('D' . $row, @$data->ketidakhadiranKegiatanJenis->nama);
            $sheet->setCellValue('E' . $row, @$data->ketidakhadiranKegiatanKeterangan->nama);
            $sheet->setCellValue('F' . $row, $data->keterangan);

            $i++;
        }

        $sheet->getStyle('A3:A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B3:B' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('C3:C' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D3:D' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E3:E' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('F3:F' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        $sheet->getStyle('C' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('A3:F' . $row)->applyFromArray($setBorderArray);

        $path = 'exports/';
        $filename = 'Data Ketidakhadiran Kegiatan - '.date('YmdHis').'.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($path.$filename);
        return Yii::$app->getResponse()->redirect($path.$filename);
    }

}
