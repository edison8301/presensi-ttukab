<?php

namespace app\modules\absensi\controllers;

use app\models\User;
use app\modules\absensi\models\Absensi;
use app\modules\absensi\models\KetidakhadiranJamKerja;
use app\modules\absensi\models\KetidakhadiranJamKerjaSearch;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * KetidakhadiranController implements the CRUD actions for Ketidakhadiran model.
 */
class KetidakhadiranJamKerjaController extends Controller
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
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return $this->accessIndex();
                        },
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return $this->accessCreate();
                        },
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return $this->accessUpdate();
                        },
                    ],
                    [
                        'actions' => ['view'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return $this->accessView();
                        },
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return $this->accessDelete();
                        },
                    ],
                    [
                        'actions' => ['set-setuju'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return $this->accessSetSetuju();
                        },
                    ],
                    [
                        'actions' => ['set-tolak'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return $this->accessSetTolak();
                        },
                    ],
                    [
                        'actions' => ['perawatan'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return User::isAdmin();
                        },
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Ketidakhadiran models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new KetidakhadiranJamKerjaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (Yii::$app->request->get('export')) {
            return $this->exportExcel(Yii::$app->request->queryParams);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Ketidakhadiran model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionSetSetuju($id)
    {
        $model = $this->findModel($id);

        if ($model->id_ketidakhadiran_jam_kerja_jenis == 1 and $model->isMasihPunyaJatahIzin() == false) {
            Yii::$app->session->setFlash('danger', 'Pegawai sudah melewati batas pengajuan izin jam kerja sebanyak 4 kali');
            return $this->redirect(Yii::$app->request->referrer);
        }

        $model->id_ketidakhadiran_jam_kerja_status = Absensi::KETIDAKHADIRAN_SETUJU;

        if($model->save() == false) {
            print_r($model->getErrors());
            foreach($model->getErrors() as $data) {
                foreach($data as $error) {
                    Yii::$app->session->setFlash('danger', 'Terjadi kesalahan: '.$error);
                }

            }

        } else {
            Yii::$app->session->setFlash('success', 'Ketidakhadiran berhasil disimpan');
        }

        return $this->redirect(Yii::$app->request->referrer);


        $referrer = Yii::$app->request->referrer;

        return $this->redirect($referrer);

    }

    public function actionSetTolak($id)
    {
        $model = $this->findModel($id);
        $model->id_ketidakhadiran_jam_kerja_status = Absensi::KETIDAKHADIRAN_TOLAK;
        $model->save();

        Yii::$app->session->setFlash('success', 'Ketidakhadiran berhasil ditolak');
        $referrer = Yii::$app->request->referrer;

        return $this->redirect($referrer);

    }

    /**
     * Creates a new Ketidakhadiran model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id_pegawai, $tanggal, $id_jam_kerja)
    {
        if (User::isPegawai()) {
            $id_pegawai = User::getIdPegawai();
        }

        $model = new KetidakhadiranJamKerja();
        $model->id_pegawai = $id_pegawai;
        $model->tanggal = $tanggal;
        $model->id_jam_kerja = $id_jam_kerja;
        $model->id_ketidakhadiran_jam_kerja_status = 2;

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {

            $referrer = $_POST['referrer'];

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Data berhasil disimpan.');
                return $this->redirect($referrer);
            }

            Yii::$app->session->setFlash('error', 'Data gagal disimpan. Silahkan periksa kembali isian Anda.');

        }

        return $this->render('create', [
            'model' => $model,
            'referrer' => $referrer,
        ]);

    }

    /**
     * Updates an existing Ketidakhadiran model.
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

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Data berhasil disimpan.');
                return $this->redirect($referrer);
            }

            Yii::$app->session->setFlash('error', 'Data gagal disimpan. Silahkan periksa kembali isian Anda.');

        }

        return $this->render('update', [
            'model' => $model,
            'referrer' => $referrer,
        ]);

    }

    public function actionPerawatan()
    {
        $query = KetidakhadiranJamKerja::find();
        $query->andWhere('id_jam_kerja = :id_jam_kerja', [
            ':id_jam_kerja' => '0',
        ]);

        print $query->count();

        KetidakhadiranJamKerja::deleteAll('id_jam_kerja = :id_jam_kerja', [
            ':id_jam_kerja' => '0',
        ]);

        print $query->count();
    }

    /**
     * Deletes an existing Ketidakhadiran model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if($model->accessDelete()==false) {
            Yii::$app->session->setFlash('error', 'Anda tidak memiliki akses untuk menghapus data');
        }

        if ($model->softDelete()) {
            Yii::$app->session->setFlash('success', 'Data berhasil dihapus');
        } else {
            Yii::$app->session->setFlash('error', 'Data gagal dihapus');
        }

        return $this->redirect(Yii::$app->request->referrer);

    }

    /**
     * Finds the Ketidakhadiran model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return KetidakhadiranJamKerja
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = KetidakhadiranJamKerja::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function accessIndex()
    {
        if (User::isAdmin()) {
            return true;
        }

        if (User::isVerifikator()) {
            return true;
        }

        if (User::isInstansi()) {
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
        if (User::isAdmin()) {
            return true;
        }

        if (User::isVerifikator()) {
            return true;
        }

        if (User::isInstansi()) {
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
        if (User::isAdmin()) {
            return true;
        }

        if (User::isVerifikator()) {
            return true;
        }

        if (User::isInstansi()) {
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
        return KetidakhadiranJamKerja::accessCreate();
    }

    public function accessDelete()
    {
        if (User::isAdmin()) {
            return true;
        }

        if (User::isVerifikator()) {
            return true;
        }

        if (User::isInstansi()) {
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
        if (User::isAdmin()) {
            return true;
        }

        if (User::isVerifikator()) {
            return true;
        }

        if(User::isAdminInstansi()) {
            return true;
        }

        return false;
    }

    public function accessSetTolak()
    {
        if (User::isAdmin()) {
            return true;
        }

        if (User::isVerifikator()) {
            return true;
        }

        if(User::isAdminInstansi()) {
            return true;
        }

        return false;
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
                    'style' => \PHPExcel_Style_Border::BORDER_THIN,
                ),
            ),
        );

        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(30);

        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'Pegawai');
        $sheet->setCellValue('C3', 'Tanggal');
        $sheet->setCellValue('D3', 'Jam Kerja');
        $sheet->setCellValue('E3', 'Jenis');
        $sheet->setCellValue('F3', 'Status');
        $sheet->setCellValue('G3', 'Berkas');
        $sheet->setCellValue('H3', 'Keterangan');

        $sheet->setCellValue('A1', 'Data Ketidakhadiran');

        $sheet->mergeCells('A1:H1');

        $sheet->getStyle('A1:H3')->getFont()->setBold(true);
        $sheet->getStyle('A1:H3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $row = 3;
        $i = 1;

        $searchModel = new KetidakhadiranJamKerjaSearch();

        foreach ($searchModel->getQuerySearch($params)->all() as $data) {
            $row++;
            $sheet->setCellValue('A' . $row, $i);
            $sheet->setCellValue('B' . $row, @$data->pegawai->nama);
            $sheet->setCellValue('C' . $row, $data->tanggal);
            $sheet->setCellValue('D' . $row, @$data->jamKerja->nama);
            $sheet->setCellValue('E' . $row, @$data->ketidakhadiranJamKerjaJenis->nama);
            $sheet->setCellValue('F' . $row, @$data->ketidakhadiranJamKerjaStatus->nama);
            $sheet->setCellValue('G' . $row, $data->berkas);
            $sheet->setCellValue('H' . $row, $data->keterangan);

            $i++;
        }

        $sheet->getStyle('A3:A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B3:B' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('C3:C' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D3:D' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E3:E' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('F3:F' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('H3:H' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        $sheet->getStyle('A3:H' . $row)->applyFromArray($setBorderArray);

        $path = 'exports/';
        $filename = 'Ketidakhadiran Jam Kerja - '.date('YmdHis').'.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($path.$filename);
        return Yii::$app->getResponse()->redirect($path . $filename);
    }

}
