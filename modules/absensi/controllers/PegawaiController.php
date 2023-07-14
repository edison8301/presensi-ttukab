<?php

namespace app\modules\absensi\controllers;

use app\models\Pegawai;
use app\models\PegawaiSearch;
use app\models\User;
use app\modules\absensi\models\Absensi;
use app\modules\absensi\models\ExportPdf;
use kartik\mpdf\Pdf;
use Yii;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\KirimKeMesinForm;
use app\modules\iclock\models\Userinfo;

/**
 * PegawaiController implements the CRUD actions for Pegawai model.
 */
class PegawaiController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'rules' => [
                    [
                        //'actions'=>['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['web-view-kehadiran'],
                        'allow' => true,
                        'roles' => ['*','@','?'],
                    ],
                ],
            ],
        ];
    }

    public function actionKirimKeMesin()
    {
        $model = new KirimKeMesinForm();
        $query = null;

        if ($model->load(Yii::$app->request->post())) {
            $query = $model->getQuerySearch();
            if ((int)$model->proses === 1) {
                if ($model->validate()) {
                    $model->kirimKeMesin();

                    Yii::$app->session->setFlash('success', 'Pegawai berhasil dikirim. Lihat informasi pengiriman '.Html::a('Lihat Disini',
                            [
                                '/iclock/devcmds/index',
                                'DevcmdsSearch[SN_id]' => $model->SN_id,
                            ]));
                    return $this->redirect(['pegawai/kirim-ke-mesin']);
                }
            }
        }

        return $this->render('kirim-ke-mesin', [
            'model' => $model,
            'query' => $query
        ]);
    }

    /**
     * Lists all Pegawai models.
     * @return mixed
     */
    public function actionIndex()
    {
        $pegawaiSearch = new PegawaiSearch();
        $dataProvider = $pegawaiSearch->search(Yii::$app->request->queryParams);

        if (isset($_GET['export-pdf'])) {
            if ($pegawaiSearch->id_instansi == null) {
                Yii::$app->session->setFlash('danger', 'Silahkan pilih perangkat daerah terlebih dahulu');
                return $this->redirect(['index']);
            }

            return $this->exportPdf($pegawaiSearch);
        }

        if (isset($_GET['refresh'])) {
            if ($pegawaiSearch->id_instansi == null) {
                Yii::$app->session->setFlash('danger', 'Silahkan pilih perangkat daerah terlebih dahulu');
                return $this->redirect(['index']);
            }

            $this->refreshPegawaiRekapAbsensi($pegawaiSearch);

            $url = Yii::$app->request->url;
            $url = str_replace('&refresh=1', '', $url);
            Yii::$app->session->setFlash('success', 'Proses refresh berhasil dilakukan');
            return $this->redirect($url);
        }

        return $this->render('index', [
            'pegawaiSearch' => $pegawaiSearch,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionRefreshPegawaiRekapAbsensi($id, $bulan)
    {
        $model = $this->findModel($id);
        $model->getPotonganBulan($bulan);
        $model->updatePegawaiRekapAbsensi($bulan);

        Yii::$app->session->setFlash('success', 'Proses refresh berhasil dilakukan');
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionIndexShiftKerja()
    {
        $searchModel = new PegawaiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (isset($_GET['export-pdf'])) {
            if ($searchModel->id_instansi == null) {
                Yii::$app->session->setFlash('danger', 'Silahkan pilih perangkat daerah terlebih dahulu');
                return $this->redirect(['index']);
            }

            return $this->exportPdf($searchModel);
        }

        return $this->render('index-shift-kerja', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexAbsensiManual()
    {
        $searchModel = new PegawaiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index-absensi-manual', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexBatasPengajuan()
    {
        $searchModel = new PegawaiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index-batas-pengajuan', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexUserinfo()
    {
        $searchModel = new PegawaiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (isset($_GET['export-pdf'])) {
            if ($searchModel->id_instansi == null) {
                Yii::$app->session->setFlash('danger', 'Silahkan pilih perangkat daerah terlebih dahulu');
                return $this->redirect(['index']);
            }

            return $this->exportPdf($searchModel);
        }

        return $this->render('index-userinfo', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexPegawaiRekapAbsensi()
    {
        $searchModel = new PegawaiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (isset($_GET['export-pdf'])) {
            if ($searchModel->id_instansi == null) {
                Yii::$app->session->setFlash('danger', 'Silahkan pilih perangkat daerah terlebih dahulu');
                return $this->redirect(['index']);
            }

            return $this->exportPdf($searchModel);
        }

        return $this->render('index-pegawai-rekap-absensi', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexTemplate()
    {
        $searchModel = new PegawaiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (isset($_GET['export-pdf'])) {
            if ($searchModel->id_instansi == null) {
                Yii::$app->session->setFlash('danger', 'Silahkan pilih perangkat daerah terlebih dahulu');
                return $this->redirect(['index']);
            }

            return $this->exportPdf($searchModel);
        }

        return $this->render('index-template', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexRekap()
    {
        $searchModel = new PegawaiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index-rekap', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Pegawai model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id = null)
    {
        if (Absensi::accessPegawaiView() == false) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if (User::isPegawai()) {
            $id = User::getIdPegawai();
        }

        $pegawai = $this->findModel($id);

        User::redirectDefaultPassword();

        $pegawaiSearch = new PegawaiSearch();
        $pegawaiSearch->tahun = User::getTahun();

        $bulan = date('n');
        if(date('j') <= 10 AND $bulan!=1) {
            $bulan = $bulan -1;
        }

        $pegawaiSearch->bulan = $bulan;
        $pegawaiSearch->load(Yii::$app->request->queryParams);

        $pegawai->getPotonganBulan($pegawaiSearch->bulan);

        $pegawaiRekapAbsensi = $pegawai->updatePegawaiRekapAbsensi($pegawaiSearch->bulan);

        return $this->render('view', [
            'pegawai' => $pegawai,
            'pegawaiSearch' => $pegawaiSearch ,
            'pegawaiRekapAbsensi' => $pegawaiRekapAbsensi,
        ]);
    }

    public function actionViewShiftKerja($id = null)
    {
        if (User::isPegawai()) {
            $id = User::getIdPegawai();
        }

        $pegawai = $this->findModel($id);

        if ($pegawai->accessViewPegawaiShiftKerja() == false) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $this->render('view-shift-kerja', [
            'pegawai' => $pegawai,
        ]);
    }

    public function actionViewUserinfo($id = null)
    {
        if (User::isPegawai()) {
            $id = User::getIdPegawai();
        }

        return $this->render('view-userinfo', [
            'pegawai' => $this->findModel($id),
        ]);
    }

    public function actionViewTemplate($id = null)
    {
        if (User::isPegawai()) {
            $id = User::getIdPegawai();
        }

        return $this->render('view-template', [
            'pegawai' => $this->findModel($id),
        ]);
    }

    public function actionUserinfo($id)
    {
        return $this->render('userinfo', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionSetJumlahUserinfo()
    {
        $query = "UPDATE pegawai SET jumlah_userinfo = (SELECT COUNT(*) FROM " . Yii::$app->params['db_iclock'] . ".userinfo WHERE " . Yii::$app->params['db_iclock'] . ".userinfo.badgenumber = pegawai.nip)";
        
        Yii::$app->db->createCommand($query)->query();

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionSetJumlahUserinfoV2()
    {
        ini_set('memory_limit', -1);
        
        $query = Pegawai::find();
        $query->andWhere('status_hapus is null');

        foreach($query->all() as $pegawai) {
            $pegawai->jumlah_userinfo = $pegawai->getManyUserinfo()->count();
            $pegawai->save();
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionSetJumlahCheckinout()
    {
        $query = "UPDATE pegawai SET jumlah_checkinout = (SELECT COUNT(*) FROM " . Yii::$app->params['db_iclock'] . ".checkinout WHERE  userid IN (SELECT userid FROM ". Yii::$app->params['db_iclock'] .".userinfo WHERE " . Yii::$app->params['db_iclock'] . ".userinfo.badgenumber = pegawai.nip))";

        Yii::$app->db->createCommand($query)->query();

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionAbsensi($id)
    {
        return $this->render('absensi', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionProfil()
    {
        return $this->redirect(['/pegawai/profil']);
        $model = User::findPegawai();

        return $this->render('profil', [
            'model' => $model,
        ]);
    }

    /*
    public function actionSetKodePegawai()
    {
    $query = Pegawai::find();
    $query->andWhere('kode_pegawai IS NULL');
    foreach($query->all() as $data)
    {
    $refPegawai = \app\models\RefPegawai::findOne($data->id);
    $data->kode_pegawai = $refPegawai->nip;
    $data->save();
    }
    }
     */

    /**
     * Creates a new Pegawai model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Pegawai();

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
     * Updates an existing Pegawai model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
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

    /**
     * Deletes an existing Pegawai model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $model->status_hapus = date('Y-m-d H:i:s');

        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Data berhasil dihapus');
        } else {
            Yii::$app->session->setFlash('error', 'Data gagal dihapus');
        }

        return $this->redirect(Yii::$app->request->referrer);

    }

    /**
     * Finds the Pegawai model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pegawai the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pegawai::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionKegiatan()
    {
        $model = User::getPegawai();
        $searchModel = new KegiatanSearch();
        $dataProvider = $searchModel->searchByPegawai(Yii::$app->request->queryParams);
        return $this->render('kegiatan', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionKegiatanBulan()
    {
        $model = User::getPegawai();
        $searchModel = new KegiatanBulanSearch();
        $dataProvider = $searchModel->searchByPegawai(Yii::$app->request->queryParams);
        return $this->render('kegiatan-bulan', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionKegiatanRealisasi()
    {
        $model = User::getPegawai();
        $searchModel = new KegiatanRealisasiSearch();
        $dataProvider = $searchModel->searchByPegawai(Yii::$app->request->queryParams);
        return $this->render('kegiatan-realisasi', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSubordinat()
    {
        $searchModel = new PegawaiSearch();
        $dataProvider = $searchModel->searchSubordinat(Yii::$app->request->queryParams);

        return $this->render('subordinat', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function refreshPegawaiRekapAbsensi($pegawaiSearch)
    {
        $query = $pegawaiSearch->querySearch(Yii::$app->request->queryParams);
        foreach ($query->all() as $pegawai) {
            $pegawai->getPotonganBulan($pegawaiSearch->bulan);
            $pegawai->updatePegawaiRekapAbsensi($pegawaiSearch->bulan);
        }
    }

    public function exportPdf($searchModel)
    {
        $modelExportPdf = ExportPdf::find()
            ->andWhere(['id_instansi' => $searchModel->id_instansi])
            ->andWhere(['bulan' => $searchModel->bulan])
            ->andWhere(['tahun' => User::getTahun()])
            ->one();

        // Cek model export pdf
        if ($modelExportPdf == null) {
            $modelExportPdf = new ExportPdf();
            $modelExportPdf->id_instansi = $searchModel->id_instansi;
            $modelExportPdf->bulan = $searchModel->bulan;
            $modelExportPdf->tahun = User::getTahun();
            $modelExportPdf->hash = Yii::$app->getSecurity()->generateRandomString(7);
            $modelExportPdf->save(false);
        }

        $query = $searchModel->querySearch(Yii::$app->request->queryParams);
        $query->orderBy(['id_golongan' => SORT_DESC]);

        $content = $this->renderPartial('export-pdf-index-statis', [
            'query' => $query,
            'searchModel' => $searchModel,
            'modelExportPdf' => $modelExportPdf,
        ]);

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8,
            // A4 paper format
            //'format' => Pdf::FORMAT_F4,
            'format' => [215.9, 330],
            'defaultFontSize' => '5',
            // portrait orientation
            'marginLeft' => 7,
            'marginRight' => 7,
            'marginTop' => 7,
            'marginBottom' => 7,
            'orientation' => Pdf::ORIENT_LANDSCAPE,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            //'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            //'cssInline' => '.kv-heading-1{font-size:18px}',
            // set mPDF properties on the fly
            'options' => ['title' => 'Export PDF Presensi'],
            // call mPDF methods on the fly
            'methods' => [
                'SetHTMLHeader' => $this->renderPartial('_barcode', ['modelExportPdf' => $modelExportPdf]),
            ],
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }

    public function actionWebViewKehadiran($nip)
    {
        $this->layout = '/webview';

        $nip = str_replace(' ','', $nip);

        $pegawai = Pegawai::findOne([
            'nip' => $nip
        ]);
        
        if($pegawai === null) {
            return $this->render('//site/web-view-kosong');
        }

        $pegawaiSearch = new PegawaiSearch();
        $pegawaiSearch->tahun = User::getTahun();

        $bulan = date('n');
        if(date('j') <= 10 AND $bulan!=1) {
            $bulan = $bulan -1;
        }

        $pegawaiSearch->bulan = $bulan;
        $pegawaiSearch->load(Yii::$app->request->queryParams);

        $pegawai->getPotonganBulan($pegawaiSearch->bulan);

        $pegawaiRekapAbsensi = $pegawai->updatePegawaiRekapAbsensi($pegawaiSearch->bulan);

        return $this->render('web-view-kehadiran', [
            'pegawai' => $pegawai,
            'pegawaiSearch' => $pegawaiSearch ,
            'pegawaiRekapAbsensi' => $pegawaiRekapAbsensi,
        ]);
    }

}
