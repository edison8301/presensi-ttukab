<?php

namespace app\modules\kinerja\controllers;

use app\components\Session;
use app\models\Catatan;
use app\modules\kinerja\models\KegiatanHarianJenis;
use Yii;
use kartik\mpdf\Pdf;
use app\modules\kinerja\models\KegiatanHarian;
use app\modules\kinerja\models\KegiatanHarianSearch;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\kinerja\models\KegiatanStatus;
use app\modules\kinerja\models\Kinerja;
use app\models\User;
use app\modules\kinerja\models\KegiatanTahunan;
use yii\web\HttpException;

/**
 * KegiatanHarianController implements the CRUD actions for KegiatanHarian model.
 */
class KegiatanHarianController extends Controller
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
        ];
    }

    /**
     * Lists all KegiatanHarian models.
     * @return mixed
     */
    public function actionIndex()
    {
        User::redirectDefaultPassword();

        if (Yii::$app->request->isPost) {
            return $this->kirim(Yii::$app->request->post('selection'));
        }

        $searchModel = new KegiatanHarianSearch();
        $searchModel->id_kegiatan_harian_versi = 1;

        if (User::isPegawai()) {
            $searchModel->setScenario(KegiatanHarianSearch::SCENARIO_PEGAWAI);
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all KegiatanHarian models.
     * @return mixed
     */
    public function actionIndexV2()
    {
        User::redirectDefaultPassword();
        KegiatanHarian::redirectV3();

        if (Yii::$app->request->isPost) {
            return $this->kirim(Yii::$app->request->post('selection'));
        }

        $searchModel = new KegiatanHarianSearch();
        $searchModel->id_kegiatan_harian_versi = 2;

        if (User::isPegawai()) {
            $searchModel->setScenario(KegiatanHarianSearch::SCENARIO_PEGAWAI);
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $id_pegawai = $searchModel->id_pegawai;

        if(User::isPegawai()) {
            $id_pegawai = User::getIdPegawai();
        }

        $queryUtama = $searchModel->querySearch(Yii::$app->request->queryParams);
        $queryUtama->andWhere(['id_pegawai' => $id_pegawai]);
        $queryUtama->andWhere(['id_kegiatan_harian_jenis' => 1]);

        $queryTambahan = $searchModel->querySearch(Yii::$app->request->queryParams);
        $queryTambahan->andWhere(['id_pegawai' => $id_pegawai]);
        $queryTambahan->andWhere(['id_kegiatan_harian_jenis' => 2]);

        return $this->render('index-v2', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'allKegiatanHarianUtama' => $queryUtama->all(),
            'allKegiatanHarianTambahan' => $queryTambahan->all(),
        ]);
    }

    /**
     * Lists all KegiatanHarian models.
     * @return mixed
     */
    public function actionIndexV3()
    {
        User::redirectDefaultPassword();

        if (Yii::$app->request->isPost) {
            return $this->kirim(Yii::$app->request->post('selection'));
        }

        $searchModel = new KegiatanHarianSearch();
        $searchModel->id_kegiatan_harian_versi = 2;

        if (User::isPegawai()) {
            $searchModel->setScenario(KegiatanHarianSearch::SCENARIO_PEGAWAI);
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $id_pegawai = $searchModel->id_pegawai;

        if(User::isPegawai()) {
            $id_pegawai = User::getIdPegawai();
        }

        $queryUtama = $searchModel->querySearch(Yii::$app->request->queryParams);
        $queryUtama->andWhere(['id_pegawai' => $id_pegawai]);
        $queryUtama->andWhere(['id_kegiatan_harian_jenis' => 1]);

        $queryTambahan = $searchModel->querySearch(Yii::$app->request->queryParams);
        $queryTambahan->andWhere(['id_pegawai' => $id_pegawai]);
        $queryTambahan->andWhere(['id_kegiatan_harian_jenis' => 2]);

        return $this->render('index-v3', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'allKegiatanHarianUtama' => $queryUtama->all(),
            'allKegiatanHarianTambahan' => $queryTambahan->all(),
        ]);
    }

    /**
     * Lists all KegiatanHarian models.
     * @return mixed
     */
    public function actionIndexV4()
    {
        User::redirectDefaultPassword();

        if (Yii::$app->request->isPost) {
            return $this->kirim(Yii::$app->request->post('selection'));
        }

        $searchModel = new KegiatanHarianSearch();
        $searchModel->id_kegiatan_harian_versi = 3;
        $searchModel->scenario = KegiatanHarianSearch::SCENARIO_PEGAWAI;

        $allKegiatanHarianUtama = $searchModel->querySearch(Yii::$app->request->queryParams)
            ->andWhere(['id_pegawai' => $searchModel->id_pegawai])
            ->andWhere(['id_kegiatan_harian_jenis' => KegiatanHarianJenis::UTAMA])
            ->all();

        $allKegiatanHarianTambahan = $searchModel->querySearch(Yii::$app->request->queryParams)
            ->andWhere(['id_pegawai' => $searchModel->id_pegawai])
            ->andWhere(['id_kegiatan_harian_jenis' => KegiatanHarianJenis::TAMBAHAN])
            ->all();

        return $this->render('index-v4', [
            'searchModel' => $searchModel,
            'allKegiatanHarianUtama' => $allKegiatanHarianUtama,
            'allKegiatanHarianTambahan' => $allKegiatanHarianTambahan,
        ]);
    }

    /**
     * Lists all KegiatanHarian models.
     * @return mixed
     */
    public function actionIndexHariIni()
    {
        User::redirectDefaultPassword();

        if (User::isPegawai() && User::getIsBelumSkp()) {
            // Yii::$app->session->setFlash('danger', 'Tidak dapat menginput CKHP karena belum ada SKP yang disetujui / terinput');
            // return $this->redirect(['/kinerja/kegiatan-tahunan/index']);
        }
        /*if ($setujui !== false) {
            $this->setujui(Yii::$app->request->queryParams);
            Yii::$app->session->setFlash('success', 'Seluruh kegiatan berhasil disetujui');
            return $this->redirect(Yii::$app->request->referrer);
        }*/
        if (Yii::$app->request->isPost) {
            return $this->kirim(Yii::$app->request->post('selection'));
        }
        $searchModel = new KegiatanHarianSearch();
        $searchModel->id_kegiatan_harian_versi = 1;

        if (User::isPegawai()) {
            $searchModel->setScenario(KegiatanHarianSearch::SCENARIO_PEGAWAI);
        }

        $dataProvider = $searchModel->searchHariIni(Yii::$app->request->queryParams);

        return $this->render('index-hari-ini', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

        /**
     * Lists all KegiatanHarian models.
     * @return mixed
     */
    public function actionIndexHariIniV2()
    {
        User::redirectDefaultPassword();

        if (Yii::$app->request->isPost) {
            return $this->kirim(Yii::$app->request->post('selection'));
        }
        $searchModel = new KegiatanHarianSearch();
        $searchModel->id_kegiatan_harian_versi = 2;

        if (User::isPegawai()) {
            $searchModel->setScenario(KegiatanHarianSearch::SCENARIO_PEGAWAI);
        }

        $searchModel->searchHariIni(Yii::$app->request->queryParams);
        $id_pegawai = $searchModel->id_pegawai;

        if (User::isPegawai() and $searchModel->isScenarioPegawai()) {
            $id_pegawai = User::getIdPegawai();
        }

        $queryUtama = $searchModel->querySearch(Yii::$app->request->queryParams);
        $queryUtama->andWhere(['id_pegawai' => $id_pegawai]);
        $queryUtama->andWhere(['id_kegiatan_harian_jenis' => 1]);

        $queryTambahan = $searchModel->querySearch(Yii::$app->request->queryParams);
        $queryTambahan->andWhere(['id_pegawai' => $id_pegawai]);
        $queryTambahan->andWhere(['id_kegiatan_harian_jenis' => 2]);


        return $this->render('index-hari-ini-v2', [
            'searchModel' => $searchModel,
            'allKegiatanHarianUtama' => $queryUtama->all(),
            'allKegiatanHarianTambahan' => $queryTambahan->all(),
        ]);
    }

    public function actionIndexBawahan()
    {
        User::redirectDefaultPassword();

        if (Yii::$app->request->isPost) {
            return $this->terapkanAksi(Yii::$app->request->post());
        }

        $searchModel = new KegiatanHarianSearch();
        $searchModel->scenario = 'atasan';
        $searchModel->id_kegiatan_harian_versi = 1;
        $dataProvider = $searchModel->searchBawahan(Yii::$app->request->queryParams);

        return $this->render('index-bawahan', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexBawahanV2()
    {
        User::redirectDefaultPassword();

        if (Yii::$app->request->isPost) {
            return $this->terapkanAksi(Yii::$app->request->post());
        }

        $searchModel = new KegiatanHarianSearch();
        $searchModel->scenario = 'atasan';
        $searchModel->id_kegiatan_harian_versi = 2;
        $dataProvider = $searchModel->searchBawahan(Yii::$app->request->queryParams);

        $queryUtama = $searchModel->getQueryBawahan(Yii::$app->request->queryParams);
        $queryUtama->andWhere(['kegiatan_harian.id_kegiatan_harian_jenis' => 1]);

        $queryTambahan = $searchModel->getQueryBawahan(Yii::$app->request->queryParams);
        $queryTambahan->andWhere(['kegiatan_harian.id_kegiatan_harian_jenis' => 2]);

        return $this->render('index-bawahan-v2', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'allKegiatanHarianUtama' => $queryUtama->all(),
            'allKegiatanHarianTambahan' => $queryTambahan->all(),
        ]);
    }

    public function actionIndexBawahanV3()
    {
        User::redirectDefaultPassword();

        if (Yii::$app->request->isPost) {
            return $this->terapkanAksi(Yii::$app->request->post());
        }

        $searchModel = new KegiatanHarianSearch();
        $searchModel->scenario = 'atasan';
        $searchModel->id_kegiatan_harian_versi = 3;
        $dataProvider = $searchModel->searchBawahan(Yii::$app->request->queryParams);

        $queryUtama = $searchModel->getQueryBawahan(Yii::$app->request->queryParams);
        $queryUtama->andWhere(['kegiatan_harian.id_kegiatan_harian_jenis' => 1]);

        $queryTambahan = $searchModel->getQueryBawahan(Yii::$app->request->queryParams);
        $queryTambahan->andWhere(['kegiatan_harian.id_kegiatan_harian_jenis' => 2]);

        $totalCount = $queryUtama->count();

        if ($totalCount < $jumlah = $queryTambahan->count()) {
            $totalCount = $jumlah;
        }

        $pagination = new Pagination(['totalCount' => $totalCount, 'pageSize' => 10]);
        $allKegiatanHarianUtama = $queryUtama->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        $allKegiatanHarianTambahan = $queryTambahan->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('index-bawahan-v3', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'allKegiatanHarianUtama' => $allKegiatanHarianUtama,
            'allKegiatanHarianTambahan' => $allKegiatanHarianTambahan,
            'pagination' => $pagination,
        ]);
    }

    protected function kirim($post)
    {
        if (@count($post) === 0 OR $post === null) {
            Yii::$app->session->setFlash('warning', 'Silahkan pilih kegiatan yang akan diproses terlebih dahulu');
            return $this->redirect(Yii::$app->request->referrer);
        }
        $i = 0;
        foreach (KegiatanHarian::find()->andWhere(['in', 'id', (array) $post])->all() as $model) {
            if (!$model->accessSetPeriksa()) {
                continue;
            }
            $model->setScenario(KegiatanHarian::SCENARIO_UPDATE_STATUS);
            $model->id_kegiatan_status = KegiatanStatus::PERIKSA;
            $model->save(false);
            $i++;
        }
        if ($i === 0) {
            Yii::$app->session->setFlash('warning', "Kegiatan yang dipilih sudah dikirim untuk diperiksa");
        } else {
            Yii::$app->session->setFlash('success', "$i Kegiatan berhasil dikirim untuk diperiksa");
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    protected function terapkanAksi($post)
    {
        ini_set('memory_limit', -1);
        if(@$post['aksi']==null) {
            Yii::$app->session->setFlash('warning', 'Silahkan pilih aksi yang akan diterapkan ke kegiatan harian');
            return $this->redirect(Yii::$app->request->referrer);
        }

        if(@count(@$post['selection'])==0 OR @$post['selection'] == null) {
            Yii::$app->session->setFlash('warning', 'Silahkan pilih kegiatan yang akan diproses terlebih dahulu');
            return $this->redirect(Yii::$app->request->referrer);
        }

        $id_kegiatan_status = null;

        if(@$post['aksi']==1) {
            $id_kegiatan_status = KegiatanStatus::SETUJU;
        }

        if(@$post['aksi']==4) {
            $id_kegiatan_status = KegiatanStatus::TOLAK;
        }

        if($id_kegiatan_status==null) {
            Yii::$app->session->setFlash('warning', 'Aksi yang dipilih invalid');
            return $this->redirect(Yii::$app->request->referrer);
        }

        $query = KegiatanHarian::find();
        $query->andWhere(['in', 'id', (array) @$post['selection']]);

        $success = 0;

        foreach ($query->all() as $model) {
            $model->id_kegiatan_status = $id_kegiatan_status;
            if($model->save()==false) {
                print_r($model->getErrors());
                die();
            }

            $success++;
        }

        Yii::$app->session->setFlash('success', 'Aksi berhasil diterapkan kepada ' . $success . ' kegiatan harian');
        return $this->redirect(Yii::$app->request->referrer);
    }

    protected function setujui($post)
    {
        if (count($post) === 0) {
            Yii::$app->session->setFlash('warning', 'Silahkan pilih kegiatan yang akan diproses terlebih dahulu');
            return $this->redirect(Yii::$app->request->referrer);
        }
        $i = 0;
        foreach (KegiatanHarian::find()->andWhere(['in', 'id', $post])->all() as $model) {
            if ($model->getIsKegiatanDisetujui()) {
                continue;
            }
            $model->setScenario(KegiatanHarian::SCENARIO_UPDATE_STATUS);
            $model->id_kegiatan_status = KegiatanStatus::SETUJU;
            $model->save(false);
            $i++;
        }
        if ($i === 0) {
            Yii::$app->session->setFlash('warning', "Kegiatan yang dipilih sudah disetujui");
        } else {
            Yii::$app->session->setFlash('success', "$i Kegiatan berhasil disetujui");
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Lists all KegiatanHarian models.
     * @return mixed
     */
    public function actionPegawaiIndex($tanggal = null)
    {
        if (empty($tanggal)) {
            $tanggal = date('Y-m-d');
        }
        $searchModel = new KegiatanHarianSearch();
        $dataProvider = $searchModel->searchBySession(Yii::$app->request->queryParams, $tanggal);

        return $this->render('pegawai/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tanggal' => $tanggal,
        ]);
    }

    public function actionSetIdPegawai()
    {
        foreach(KegiatanHarian::find()->all() as $data) {
            if(!empty($data->kegiatanTahunan)) {
                $data->id_pegawai = $data->kegiatanTahunan->id_pegawai;
                $data->save();
            }
        }
    }

    public function actionPerawatan()
    {
        $query = KegiatanHarian::find();
        $query->andWhere('id_kegiatan_tahunan IS NULL');

        foreach($query->all() as $data) {
            $data->id_kegiatan_harian_jenis = Kinerja::KEGIATAN_TAMBAHAN;
            $data->id_kegiatan_harian_tambahan = 1;
            $data->save();
        }

        $query = KegiatanHarian::find();
        $query->andWhere('id_kegiatan_tahunan IS NOT NULL');

        foreach($query->all() as $data) {
            $data->id_kegiatan_harian_jenis = Kinerja::KEGIATAN_SKP;
            $data->save();
        }
    }

    /**
     * Lists all KegiatanHarian models.
     * @return mixed
     */
    /*
    public function actionSubordinatIndex($tanggal = null)
    {
        if (empty($tanggal)) {
            $tanggal = date('Y-m-d');
        }
        $searchModel = new KegiatanHarianSearch();
        $dataProvider = $searchModel->searchSubordinat(Yii::$app->request->queryParams, $tanggal);

        return $this->render('subordinat/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tanggal' => $tanggal,
        ]);
    }
    */

    /**
     * Displays a single KegiatanHarian model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id,$mode=null)
    {
        $model = $this->findModel($id);
        $model->mode = $mode;

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionViewV3($id,$mode=null)
    {
        $model = $this->findModel($id);
        $model->mode = $mode;

        return $this->render('view-v3', [
            'model' => $model,
        ]);
    }

    public function actionViewV4($id)
    {
        $model = $this->findModel($id);

        return $this->render('view-v4', [
            'model' => $model,
        ]);
    }

    public function actionViewCatatan($id)
    {
        $model = $this->findModel($id);

        if(Yii::$app->request->post('catatan')) {
            $model = new Catatan();
            $model->id_kegiatan_harian = $id;
            $model->id_induk = null;
            $model->id_user = Session::getIdUser();
            $model->catatan = Yii::$app->request->post('catatan');
            $model->waktu_buat = date('Y-m-d H:i:s');

            if(!$model->save()) {
                print_r($model->getErrors());die;
            }

            Yii::$app->session->setFlash('session', 'Catatan berhasil disimpan');
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->render('view-catatan', [
            'model' => $model,
        ]);
    }

    public function actionSetPeriksa($id)
    {
        $model = $this->findModel($id);

        $model->setScenario(KegiatanHarian::SCENARIO_UPDATE_STATUS);
        $model->setIdKegiatanStatus(KegiatanStatus::PERIKSA);
        $model->save();

        Yii::$app->session->setFlash('success','Data berhasil dikirim untuk diperiksa');

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionSetSetuju($id)
    {
        ini_set('memory_limit', -1);
        $model = $this->findModel($id);
        $model->setScenario(KegiatanHarian::SCENARIO_UPDATE_STATUS);
        $model->setIdKegiatanStatus(KegiatanStatus::SETUJU);

        if($model->save()) {
            $model->updateRealisasiKegiatanBulanan();
            Yii::$app->session->setFlash('success','Kegiatan berhasil disetujui');
        } else {
            Yii::$app->session->setFlash('danger','Terjadi kesalahan. Kegiatan GAGAL disetujui');
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionSetKonsep($id)
    {
        $model = $this->findModel($id);
        $model->setScenario(KegiatanHarian::SCENARIO_UPDATE_STATUS);
        $model->setIdKegiatanStatus(KegiatanStatus::KONSEP);

        if($model->save()) {
            $model->updateRealisasiKegiatanBulanan();
            Yii::$app->session->setFlash('success','Kegiatan berhasil diubahjadi konsep');
        } else {
            Yii::$app->session->setFlash('danger','Terjadi kesalahan. Kegiatan GAGAL diubah jadi konsep');
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionSetTolak($id)
    {
        $model = $this->findModel($id);

        $model->setScenario(KegiatanHarian::SCENARIO_UPDATE_STATUS);
        $model->setIdKegiatanStatus(KegiatanStatus::TOLAK);
        $model->save();

        Yii::$app->session->setFlash('success','Kegiatan berhasil ditolak');

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Displays a single KegiatanHarian model.
     * @param integer $id
     * @return mixed
     */
    public function actionPegawaiView($id,$mode='pegawai')
    {
        $model = $this->findModel($id);
        $model->mode = $mode;

        return $this->render('pegawai/view', [
            'model' => $model,
        ]);
    }

    /**
     * Displays a single KegiatanHarian model.
     * @param integer $id
     * @return mixed
     */
    /*
    public function actionSubordinatView($id)
    {
        return $this->render('subordinat/view', [
            'model' => $this->findModel($id),
        ]);
    }
    */

    /**
     * Creates a new KegiatanHarian model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id_kegiatan_harian_jenis, $id_kegiatan_tahunan = null, $tanggal=null)
    {
        /*if (User::isPegawai() && User::getIsBelumSkp()) {
            Yii::$app->session->setFlash('danger', 'Tidak dapat menginput CKHP karena belum ada SKP yang disetujui / terinput');
            return $this->redirect(['/kinerja/kegiatan-tahunan/index']);
        }*/

        if($tanggal==null) {
            $tanggal = date('Y-m-d');
        }

        $model = new KegiatanHarian;
        $model->loadDefaultAttributes();

        $model->id_kegiatan_harian_versi = 1;
        $model->id_kegiatan_harian_jenis = $id_kegiatan_harian_jenis;
        $model->tanggal = $tanggal;
        $model->id_kegiatan_tahunan = $id_kegiatan_tahunan;
        $model->jam_mulai = date('H:i');
        $model->jam_selesai = date('H:i');
        $model->id_pegawai = User::getIdPegawai();

        $model->setScenarioKegiatan();

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {
            $referrer = $_POST['referrer'];
            if($model->save()) {
                $model->updateRealisasiKegiatanBulanan();
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
     * Creates a new KegiatanHarian model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateV2($id_kegiatan_harian_jenis, $id_kegiatan_tahunan = null, $tanggal=null)
    {
        /*if (User::isPegawai() && User::getIsBelumSkp()) {
            Yii::$app->session->setFlash('danger', 'Tidak dapat menginput CKHP karena belum ada SKP yang disetujui / terinput');
            return $this->redirect(['/kinerja/kegiatan-tahunan/index']);
        }*/

        KegiatanHarian::redirectV3();

        if($tanggal==null) {
            $tanggal = date('Y-m-d');
        }

        $model = new KegiatanHarian;
        $model->loadDefaultAttributes();

        $model->id_kegiatan_harian_versi = 2;
        $model->id_kegiatan_harian_jenis = $id_kegiatan_harian_jenis;
        $model->tanggal = $tanggal;
        $model->id_kegiatan_tahunan = $id_kegiatan_tahunan;
        $model->jam_mulai = date('H:i');
        $model->jam_selesai = date('H:i');
        $model->id_pegawai = User::getIdPegawai();

        $model->setScenarioKegiatan();

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {
            $model->updateTanggal();
            $referrer = $_POST['referrer'];
            if($model->save()) {
                $model->updateRealisasiKegiatanBulanan();
                Yii::$app->session->setFlash('success','Data berhasil disimpan.');
                return $this->redirect($referrer);
            }
            Yii::$app->session->setFlash('error','Data gagal disimpan. Silahkan periksa kembali isian Anda.');
        }

        return $this->render('create-v2', [
            'model' => $model,
            'referrer'=>$referrer
        ]);

    }

    /**
     * Creates a new KegiatanHarian model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateV3($id_kegiatan_harian_jenis, $id_kegiatan_tahunan=null, $tanggal=null)
    {
        if (Session::isKinerjaPP30Aktif() == false) {
            Yii::$app->session->setFlash('danger', 'Kinerja PP 30/2019 sudah tidak digunakan, silahkan gunakan Permenpan 6 Tahun 2022');
            return $this->redirect(Yii::$app->request->referrer);
        }

        $model = new KegiatanHarian;
        $model->loadDefaultAttributes();

        $model->id_kegiatan_harian_versi = 2;
        $model->id_kegiatan_harian_jenis = $id_kegiatan_harian_jenis;
        $model->id_kegiatan_tahunan = $id_kegiatan_tahunan;
        $model->tanggal = $tanggal;
        $model->jam_mulai = date('H:i');
        $model->jam_selesai = date('H:i');
        $model->id_pegawai = User::getIdPegawai();
        $model->setWaktuDibuat();
        $model->updateTanggal();

        if ($model->id_kegiatan_harian_jenis == KegiatanHarianJenis::TAMBAHAN) {
            $model->setScenarioKegiatan();
        }

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {
            $model->updateTanggal();
            $referrer = $_POST['referrer'];
            if($model->save()) {
                $model->updateRealisasiKegiatanBulanan();
                Yii::$app->session->setFlash('success','Data berhasil disimpan.');
                return $this->redirect($referrer);
            }
            Yii::$app->session->setFlash('error','Data gagal disimpan. Silahkan periksa kembali isian Anda.');
        }

        return $this->render('create-v3', [
            'model' => $model,
            'referrer' => $referrer
        ]);
    }

    public function actionCreateV4($id_kegiatan_harian_jenis, $tanggal=null)
    {
        $model = new KegiatanHarian();
        $model->loadDefaultAttributes();

        $model->id_kegiatan_harian_versi = 3    ;
        $model->id_kegiatan_harian_jenis = $id_kegiatan_harian_jenis;
        $model->tanggal = $tanggal;
        $model->jam_mulai = date('H:i');
        $model->jam_selesai = date('H:i');
        $model->id_pegawai = User::getIdPegawai();
        $model->updateTanggal();

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {

            $referrer = $_POST['referrer'];

            $model->updateTanggal();
            $model->setWaktuDibuat();

            if($model->save()) {
                $model->updateRealisasiKegiatanBulanan();

                Yii::$app->session->setFlash('success','Data berhasil disimpan.');
                return $this->redirect($referrer);
            }

            Yii::$app->session->setFlash('error','Data gagal disimpan. Silahkan periksa kembali isian Anda.');
        }

        return $this->render('create-v4', [
            'model' => $model,
            'referrer' => $referrer
        ]);
    }

    /**
     * Updates an existing KegiatanHarian model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        /*if (User::isPegawai() && User::getIsBelumSkp()) {
            Yii::$app->session->setFlash('danger', 'Tidak dapat menginput CKHP karena belum ada SKP yang disetujui / terinput');
            return $this->redirect(['/kinerja/kegiatan-tahunan/index']);
        }*/
        $model = $this->findModel($id);
        $model->loadDefaultAttributes();

        $model->setScenarioKegiatan();

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {

            $referrer = $_POST['referrer'];

            if($model->save())
            {
                $model->updateRealisasiKegiatanBulanan();
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
     * Updates an existing KegiatanHarian model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdateV2($id)
    {
        /*if (User::isPegawai() && User::getIsBelumSkp()) {
            Yii::$app->session->setFlash('danger', 'Tidak dapat menginput CKHP karena belum ada SKP yang disetujui / terinput');
            return $this->redirect(['/kinerja/kegiatan-tahunan/index']);
        }*/

        KegiatanHarian::redirectV3();

        $model = $this->findModel($id);
        $model->loadDefaultAttributes();

        $model->setScenarioKegiatan();

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {

            $referrer = $_POST['referrer'];

            if($model->save())
            {
                $model->updateRealisasiKegiatanBulanan();
                Yii::$app->session->setFlash('success','Data berhasil disimpan.');
                return $this->redirect($referrer);
            }

            Yii::$app->session->setFlash('error','Data gagal disimpan. Silahkan periksa kembali isian Anda.');


        }

        return $this->render('update-v2', [
            'model' => $model,
            'referrer'=>$referrer
        ]);

    }

    /**
     * Updates an existing KegiatanHarian model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdateV3($id)
    {
        $model = $this->findModel($id);

        $tanggal_lama = $model->tanggal;

        if ($model->id_kegiatan_harian_jenis == KegiatanHarianJenis::TAMBAHAN) {
            $model->setScenarioKegiatan();
        }

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {

            $model->loadDefaultAttributes();
            $model->tanggal = $tanggal_lama;

            $referrer = $_POST['referrer'];

            if($model->save()) {
                $model->updateRealisasiKegiatanBulanan();
                Yii::$app->session->setFlash('success','Data berhasil disimpan.');
                return $this->redirect($referrer);
            }

            Yii::$app->session->setFlash('error','Data gagal disimpan. Silahkan periksa kembali isian Anda.');
        }

        return $this->render('update-v3', [
            'model' => $model,
            'referrer'=>$referrer
        ]);
    }

    /**
     * Updates an existing KegiatanHarian model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdateV4($id)
    {
        $model = $this->findModel($id);

        $tanggal_lama = $model->tanggal;

        if ($model->id_kegiatan_harian_jenis == KegiatanHarianJenis::TAMBAHAN) {
            $model->setScenarioKegiatan();
        }

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {

            $model->loadDefaultAttributes();
            $model->tanggal = $tanggal_lama;

            $referrer = $_POST['referrer'];

            if($model->save()) {
                $model->updateRealisasiKegiatanBulanan();
                Yii::$app->session->setFlash('success','Data berhasil disimpan.');
                return $this->redirect($referrer);
            }

            Yii::$app->session->setFlash('error','Data gagal disimpan. Silahkan periksa kembali isian Anda.');
        }

        return $this->render('update-v4', [
            'model' => $model,
            'referrer'=>$referrer
        ]);
    }

    /**
     * Deletes an existing KegiatanHarian model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = KegiatanHarian::findOne($id);
        if($model->softDelete()) {
            Yii::$app->session->setFlash('success','Data berhasil dihapus');
        } else {
            Yii::$app->session->setFlash('error','Data gagal dihapus');
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the KegiatanHarian model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return KegiatanHarian the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if ((
            $model = KegiatanHarian::find()
            ->andWhere(['id' => $id])
            ->aktif()
            ->one()
        ) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionRekap($export = false)
    {
        $searchModel = new KegiatanHarianSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if ($export) {
            return $this->exportRekapPdf($searchModel);
        }
        return $this->render('rekap', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function exportRekapPdf(KegiatanHarianSearch $searchModel)
    {
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => [215.9,330],
            'defaultFontSize' => '4',
            'cssInline' => '.bg-gray {
                color: #000;
                background-color: #d2d6de !important;
            } td { padding:5px; }',
            'marginLeft'=>7,
            'marginRight'=>7,
            'marginTop'=>7,
            'marginBottom'=>7,
            'orientation' => Pdf::ORIENT_LANDSCAPE,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $this->renderPartial('_rekap-pdf', [
                'searchModel' => $searchModel,
            ]),
            'options' => ['title' => 'Export PDF Rekap CKHP SKP'],
        ]);

        return $pdf->render();
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionTolak($id)
    {
        $model = $this->findModel($id);

        if($model->accessSetTolak()==false) {
            throw new HttpException(403, 'Anda tidak meiliki akses ke halaman ini');
        }

        $referrer = Yii::$app->request->referrer;

        if($model->load(Yii::$app->request->post())) {
            $model->setScenario(KegiatanHarian::SCENARIO_UPDATE_STATUS);
            $model->setIdKegiatanStatus(KegiatanStatus::TOLAK);
            $model->save();

            $referrer = $_POST['referrer'];

            Yii::$app->session->setFlash('success','Data berhasil ditolak');

            return $this->redirect($referrer);
        }

        return $this->render('_form-tolak', [
            'model' => $model,
            'referrer' => $referrer,
        ]);
    }

    public function actionCekTidakSesuai()
    {
        $query = KegiatanHarian::find();
        $query->andWhere('tanggal >= :tanggal', [
            ':tanggal' => '2022-06-01',
        ]);

        return $this->render('cek-tidak-sesuai', [
            'allKegiatanHarian' => $query->all(),
        ]);
    }
}
