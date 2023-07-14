<?php

namespace app\modules\kinerja\controllers;

use app\components\Helper;
use app\components\Session;
use app\models\Catatan;
use app\modules\kinerja\models\SkpIkiMik;
use Yii;
use app\modules\kinerja\models\KegiatanTahunan;
use app\modules\kinerja\models\KegiatanTahunanSearch;
use yii\base\BaseObject;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;
use app\modules\kinerja\models\KegiatanStatus;
use app\models\Pegawai;
use kartik\mpdf\Pdf;
use yii\helpers\Json;

/**
 * KegiatanTahunanController implements the CRUD actions for KegiatanTahunan model.
 */
class KegiatanTahunanController extends Controller
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
     * Lists all KegiatanTahunan models.
     * @return mixed
     */
    public function actionIndex($debug=false)
    {
        User::redirectDefaultPassword();

        $searchModel = new KegiatanTahunanSearch();
        $searchModel->id_kegiatan_tahunan_versi = 1;
        $searchModel->scenario = 'pegawai';

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (Yii::$app->request->isPost) {
            if (User::isAdmin()) {
                return $this->konsep(Yii::$app->request->post('selection'));
            } else {
                return $this->kirim(Yii::$app->request->post('selection'));
            }
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'debug'=>$debug
        ]);
    }

    /**
     * Lists all KegiatanTahunan models.
     * @return mixed
     */
    public function actionIndexMik($id_instansi_pegawai_skp)
    {
        User::redirectDefaultPassword();

        $searchModel = new KegiatanTahunanSearch();
        $searchModel->id_instansi_pegawai_skp = $id_instansi_pegawai_skp;
        $searchModel->id_kegiatan_tahunan_versi = 3;
        $searchModel->scenario = KegiatanTahunanSearch::SCENARIO_PEGAWAI;

        $allKegiatanTahunan = $searchModel->querySearch(Yii::$app->request->queryParams)
            ->joinWith(['kegiatanRhk'])
            ->andWhere('kegiatan_rhk.id_induk is null')
            ->all();

        return $this->render('index-mik', [
            'allKegiatanTahunan' => $allKegiatanTahunan,
        ]);
    }

    /**
     * Lists all KegiatanTahunan models.
     * @return mixed
     */
    public function actionIndexV2($debug=false)
    {
        User::redirectDefaultPassword();

        $searchModel = new KegiatanTahunanSearch();
        $searchModel->scenario = 'pegawai';

        $searchModel->load(Yii::$app->request->queryParams);

        $id_pegawai = '-';

        if($searchModel->id_pegawai !== null) {
            $id_pegawai = $searchModel->id_pegawai;
        }

        if(User::isPegawai()) {
            $id_pegawai = User::getIdPegawai();
        }

        $queryKegiatanUtama = $searchModel->querySearch(Yii::$app->request->queryParams);
        $queryKegiatanUtama->andWhere(['kegiatan_tahunan.id_pegawai' => $id_pegawai]);
        $queryKegiatanUtama->andWhere(['kegiatan_tahunan.id_kegiatan_tahunan_versi' => 2]);
        $queryKegiatanUtama->andWhere(['kegiatan_tahunan.id_kegiatan_tahunan_jenis' => 1]);
        $queryKegiatanUtama->orderBy(['kegiatan_tahunan.id' => SORT_DESC]);

        $allKegiatanTahunanUtamaInduk = $queryKegiatanUtama->all();

        $queryKegiatanTambahan = $searchModel->querySearch(Yii::$app->request->queryParams);
        $queryKegiatanTambahan->andWhere(['kegiatan_tahunan.id_pegawai' => $id_pegawai]);
        $queryKegiatanTambahan->andWhere(['kegiatan_tahunan.id_kegiatan_tahunan_versi' => 2]);
        $queryKegiatanTambahan->andWhere(['kegiatan_tahunan.id_kegiatan_tahunan_jenis' => 2]);
        $queryKegiatanTambahan->orderBy(['kegiatan_tahunan.id' => SORT_DESC]);

        $allKegiatanTahunanTambahanInduk = $queryKegiatanTambahan->all();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (Yii::$app->request->isPost) {
            if (User::isAdmin()) {
                return $this->konsep(Yii::$app->request->post('selection'));
            } else {
                return $this->kirim(Yii::$app->request->post('selection'));
            }
        }

        return $this->render('index-v2', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'debug' => $debug,
            'allKegiatanTahunanUtamaInduk' => $allKegiatanTahunanUtamaInduk,
            'allKegiatanTahunanTambahanInduk' => $allKegiatanTahunanTambahanInduk,
        ]);
    }

    public function actionIndexBawahan($debug = false)
    {
        User::redirectDefaultPassword();

        if (Yii::$app->request->isPost) {
            return $this->terapkanAksi(Yii::$app->request->post());
        }

        if (Yii::$app->request->get('export')) {
            return $this->exportExcel(Yii::$app->request->queryParams);
        }

        $searchModel = new KegiatanTahunanSearch();
        $searchModel->id_kegiatan_tahunan_versi = 1;
        $searchModel->scenario = 'atasan';
        $searchModel->mode = 'atasan';
        $dataProvider = $searchModel->searchBawahan(Yii::$app->request->queryParams);

        return $this->render('index-bawahan', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'debug' => $debug
        ]);
    }

    public function actionIndexBawahanV2($debug = false)
    {
        User::redirectDefaultPassword();

        if (Yii::$app->request->isPost) {
            return $this->terapkanAksi(Yii::$app->request->post());
        }

        if (Yii::$app->request->get('export')) {
            return $this->exportExcel(Yii::$app->request->queryParams);
        }

        $searchModel = new KegiatanTahunanSearch();
        $searchModel->scenario = 'atasan';
        $searchModel->mode = 'atasan';
        $dataProvider = $searchModel->searchBawahan(Yii::$app->request->queryParams);

        $queryUtama = KegiatanTahunan::find();
        $queryUtama->joinWith(['jabatan', 'instansiPegawaiSkp']);
        $queryUtama->andWhere(['jabatan.id_induk' => User::getIdJabatanBerlaku()]);
        $queryUtama->andWhere(['kegiatan_tahunan.id_kegiatan_tahunan_versi' => 2]);
        $queryUtama->andWhere(['kegiatan_tahunan.id_kegiatan_tahunan_jenis' => 1]);
        $queryUtama->andWhere(['kegiatan_tahunan.tahun' => Session::getTahun()]);
        $queryUtama->andFilterWhere(['kegiatan_tahunan.id_kegiatan_status' => $searchModel->id_kegiatan_status]);
        $queryUtama->andFilterWhere(['kegiatan_tahunan.id_pegawai' => $searchModel->id_pegawai]);
        $queryUtama->andFilterWhere(['like', 'instansi_pegawai_skp.nomor', $searchModel->nomor_skp]);

        $queryTambahan = KegiatanTahunan::find();
        $queryTambahan->joinWith(['jabatan', 'instansiPegawaiSkp']);
        $queryTambahan->andWhere(['jabatan.id_induk' => User::getIdJabatanBerlaku()]);
        $queryTambahan->andWhere(['kegiatan_tahunan.id_kegiatan_tahunan_versi' => 2]);
        $queryTambahan->andWhere(['kegiatan_tahunan.id_kegiatan_tahunan_jenis' => 2]);
        $queryTambahan->andWhere(['kegiatan_tahunan.tahun' => Session::getTahun()]);
        $queryTambahan->andFilterWhere(['kegiatan_tahunan.id_kegiatan_status' => $searchModel->id_kegiatan_status]);
        $queryTambahan->andFilterWhere(['kegiatan_tahunan.id_pegawai' => $searchModel->id_pegawai]);
        $queryTambahan->andFilterWhere(['like', 'instansi_pegawai_skp.nomor', $searchModel->nomor_skp]);

        return $this->render('index-bawahan-v2', [
            'allKegiatanTahunanBawahanUtama' => $queryUtama->all(),
            'allKegiatanTahunanBawahanTambahan' => $queryTambahan->all(),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'debug' => $debug
        ]);
    }

    public function actionIndexBawahanV3()
    {
        if (Yii::$app->request->isPost) {
            return $this->terapkanAksi(Yii::$app->request->post());
        }

        $searchModel = new KegiatanTahunanSearch();
        $searchModel->scenario = 'atasan';

        $allKegiatanTahunanUtama = $searchModel->getQueryBawahan(Yii::$app->request->queryParams)
            ->andWhere([
                'kegiatan_tahunan.id_kegiatan_tahunan_versi' => 3,
                'kegiatan_tahunan.id_kegiatan_tahunan_jenis' => KegiatanTahunan::UTAMA,
            ])
            ->orderBy(['kegiatan_tahunan.id_kegiatan_rhk' => SORT_ASC])
            ->all();

        $allKegiatanTahunanTambahan = $searchModel->getQueryBawahan(Yii::$app->request->queryParams)
            ->andWhere([
                'kegiatan_tahunan.id_kegiatan_tahunan_versi' => 3,
                'kegiatan_tahunan.id_kegiatan_tahunan_jenis' => KegiatanTahunan::TAMBAHAN,
            ])
            ->orderBy(['kegiatan_tahunan.id_kegiatan_rhk' => SORT_ASC])
            ->all();

        return $this->render('index-bawahan-v3', [
            'searchModel' => $searchModel,
            'allKegiatanTahunanUtama' => $allKegiatanTahunanUtama,
            'allKegiatanTahunanTambahan' => $allKegiatanTahunanTambahan,
        ]);
    }

    /**
     * Lists all KegiatanTahunan models.
     * @return mixed
     */
    public function actionPegawaiIndex()
    {
        $searchModel = new KegiatanTahunanSearch();
        $dataProvider = $searchModel->searchBySession(Yii::$app->request->queryParams);

        if (Yii::$app->request->get('export')) {
            return $this->exportExcel(Yii::$app->request->queryParams);
        }

        return $this->render('pegawai/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single KegiatanTahunan model.
     * @param integer $id
     * @return mixed
     * @throws HttpException
     */
    public function actionView($id,$mode=null)
    {
        $model = $this->findModel($id);

        if ($model->accessView()==false) {
            throw new HttpException(403, 'Anda tidak meiliki akses ke halaman ini');
        }

        $model->mode = $mode;

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionViewV2($id,$mode=null)
    {
        $model = $this->findModel($id);

        if ($model->accessView()==false) {
            throw new HttpException(403, 'Anda tidak meiliki akses ke halaman ini');
        }

        $model->mode = $mode;

        return $this->render('view-v2', [
            'model' => $model,
        ]);
    }

    public function actionViewV3($id)
    {
        $model = $this->findModel($id);

        return $this->render('view-v3', [
            'model' => $model,
        ]);
    }

    public function actionViewMik($id)
    {
        $model = $this->findModel($id);

        $skpIkiMik = SkpIkiMik::findOrCreate([
            'id_skp' => $model->id_instansi_pegawai_skp,
            'id_skp_iki' => $model->id,
        ]);

        return $this->render('view-mik', [
            'model' => $model,
            'skpIkiMik' => $skpIkiMik,
        ]);
    }

    public function actionViewCatatan($id)
    {
        $model = $this->findModel($id);

        if(Yii::$app->request->post('catatan')) {
            $model = new Catatan();
            $model->id_kegiatan_tahunan = $id;
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

    public function actionMatriks($id_instansi_pegawai = null)
    {
        User::redirectDefaultPassword();

        $searchModel = new KegiatanTahunanSearch([
            'scenario' => KegiatanTahunanSearch::SCENARIO_PEGAWAI,
            'id_kegiatan_tahunan_versi' => 1,
        ]);

        $searchModel->load(Yii::$app->request->queryParams);

        $query = $searchModel->querySearch(Yii::$app->request->queryParams);

        $query->andWhere('kegiatan_tahunan.id_induk IS NULL');
        $query->andWhere(['instansi_pegawai.id_pegawai'=>User::getIdPegawai()]);
        $query->andWhere(['kegiatan_tahunan.tahun'=>User::getTahun()]);

        $query->with(['manyKegiatanBulanan','instansiPegawaiSkp']);

        $allKegiatanTahunanInduk = $query->all();

        return $this->render('matriks', [
            'allKegiatanTahunanInduk' => $allKegiatanTahunanInduk,
            'searchModel'=>$searchModel
        ]);
    }

    public function actionMatriksV2($id_instansi_pegawai = null)
    {
        $searchModel = new KegiatanTahunanSearch([
            'scenario' => KegiatanTahunanSearch::SCENARIO_PEGAWAI,
            'id_kegiatan_tahunan_versi' => 2,
        ]);
        User::redirectDefaultPassword();

        $searchModel->load(Yii::$app->request->queryParams);

        $query = $searchModel->querySearch(Yii::$app->request->queryParams);
        $query->andWhere(['id_kegiatan_tahunan_jenis' => 1]);
        $query->andWhere('kegiatan_tahunan.id_induk IS NULL');
        $query->andWhere(['instansi_pegawai.id_pegawai'=>User::getIdPegawai()]);
        $query->andWhere(['kegiatan_tahunan.tahun'=>User::getTahun()]);
        $query->with(['manyKegiatanBulanan','instansiPegawaiSkp']);

        $allKegiatanTahunanUtamaInduk = $query->all();

        $query2 = $searchModel->querySearch(Yii::$app->request->queryParams);
        $query2->andWhere(['id_kegiatan_tahunan_jenis' => 2]);
        $query2->andWhere('kegiatan_tahunan.id_induk IS NULL');
        $query2->andWhere(['instansi_pegawai.id_pegawai'=>User::getIdPegawai()]);
        $query2->andWhere(['kegiatan_tahunan.tahun'=>User::getTahun()]);
        $query2->with(['manyKegiatanBulanan','instansiPegawaiSkp']);

        $allKegiatanTahunanTambahanInduk = $query2->all();

        return $this->render('matriks-v2', [
            'allKegiatanTahunanUtamaInduk' => $allKegiatanTahunanUtamaInduk,
            'allKegiatanTahunanTambahanInduk' => $allKegiatanTahunanTambahanInduk,
            'searchModel'=>$searchModel
        ]);
    }

    public function actionMatriksBulanan($id_instansi_pegawai = null, $bulan = null)
    {
        if ($id_instansi_pegawai === null) {
            $id_instansi_pegawai = Yii::$app->user->identity->pegawai->getInstansiPegawaiBerlaku()->id;
        }
        if ($bulan === null) {
            $bulan = date('m');
        }
        $allKegiatanTahunanInduk = KegiatanTahunan::allIndukByIdPegawai(User::getIdPegawai(), $id_instansi_pegawai, $bulan);
        return $this->render('matriks-bulanan', [
            'allKegiatanTahunanInduk' => $allKegiatanTahunanInduk,
            'bulan' => $bulan,
            'id_instansi_pegawai' => $id_instansi_pegawai,
        ]);
    }

    public function actionSkp($id_pegawai=null)
    {
        if (User::isPegawai()) {
            $id_pegawai = User::getIdPegawai();
        }

        return $this->render('skp', [
            'id_pegawai'=>$id_pegawai
        ]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws HttpException
     */
    public function actionSetPeriksa($id)
    {
        $model = $this->findModel($id);

        if ($model->accessSetPeriksa()==false) {
            throw new HttpException(403, 'Anda tidak memiliki akses ke halaman ini');
        }

        if ($model->target_kuantitas == null OR $model->target_kuantitas == 0) {
            Yii::$app->session->setFlash('danger','Target kuantitas kegiatan tahunan tidak boleh kosong');
            return $this->redirect(Yii::$app->request->referrer);
        }

        if ($model->target_kuantitas != $model->getSumTargetKegiatanBulanan()) {
            $message = 'Jumlah target kegiatan bulanan belum sesuai dengan jumlah target
                kegiatan tahunan. Periksa kembali matriks target bulannya dari bulan '. Helper::getBulanLengkap($model->getDariBulanTarget()) .'
                s.d. '. Helper::getBulanLengkap($model->getSampaiBulanTarget()) .' harus diisi.';

            Yii::$app->session->setFlash('danger',$message);
            return $this->redirect(Yii::$app->request->referrer);
        }

        if(($bulan = $model->validateTargetPerBulan()) != 0) {
            Yii::$app->session->setFlash('danger','Harus ada minimal 1 target dari seluruh kegiatan pada bulan '. Helper::getBulanLengkap($bulan));
            return $this->redirect(Yii::$app->request->referrer);
        }

        if(Session::getTahun() >= 2021
            AND $model->id_kegiatan_tahunan_atasan == null
            AND $model->status_kegiatan_tahunan_atasan == 1
            AND $model->instansiPegawai->jabatan->status_kepala == false
            AND $model->id_kegiatan_tahunan_jenis == 1
        ) {
            Yii::$app->session->setFlash('danger','Silahkan pilih dukungan kegiatan atasan terlebih dahulu sebeluum mengirimkan kegiatan untuk diperiksa atasan');
            return $this->redirect(Yii::$app->request->referrer);
        }

        $model->setScenario(KegiatanTahunan::SCENARIO_UPDATE_STATUS);
        $model->setIdKegiatanStatus(KegiatanStatus::PERIKSA);
        $model->save();

        Yii::$app->session->setFlash('success','Data berhasil dikirim untuk diperiksa');

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * @throws NotFoundHttpException
     * @throws HttpException
     */
    public function actionSetPeriksaV3($id)
    {
        $model = $this->findModel($id);

        if ($model->accessSetPeriksa() == false) {
            throw new HttpException(403, 'Anda tidak memiliki akses ke halaman ini');
        }

        if ($model->target == null OR $model->target == 0) {
            Yii::$app->session->setFlash('danger','Target iki tahunan tidak boleh kosong');
            return $this->redirect(Yii::$app->request->referrer);
        }

        if ($model->target != $model->getSumTargetKegiatanBulanan()) {
            $message = 'Jumlah target iki bulanan belum sesuai dengan jumlah target
                iki tahunan. Periksa kembali matriks target bulannya dari bulan '. Helper::getBulanLengkap($model->getDariBulanTarget()) .'
                s.d. '. Helper::getBulanLengkap($model->getSampaiBulanTarget()) .' harus diisi.';

            Yii::$app->session->setFlash('danger',$message);
            return $this->redirect(Yii::$app->request->referrer);
        }

        if (($bulan = $model->validateTargetPerBulan()) != 0) {
            Yii::$app->session->setFlash('danger','Harus ada minimal 1 target dari seluruh kegiatan pada bulan '. Helper::getBulanLengkap($bulan));
            return $this->redirect(Yii::$app->request->referrer);
        }

        $id_kegiatan_status = KegiatanStatus::PERIKSA;
        $alert = 'Data berhasil dikirim untuk diperiksa';

        if ($model->isJpt()) {
            $id_kegiatan_status = KegiatanStatus::SETUJU;
            $alert = 'Data berhasil dikirim';
        }

        $model->setScenario(KegiatanTahunan::SCENARIO_UPDATE_STATUS);
        $model->setIdKegiatanStatus($id_kegiatan_status);
        $model->save();

        Yii::$app->session->setFlash('success', $alert);

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws HttpException
     */
    public function actionSetSetuju($id)
    {
        $model = $this->findModel($id);

        if($model->accessSetSetuju()==false) {
            throw new HttpException(403, 'Anda tidak meiliki akses ke halaman ini');
        }

        $model->setScenario(KegiatanTahunan::SCENARIO_UPDATE_STATUS);
        $model->setIdKegiatanStatus(KegiatanStatus::SETUJU);
        $model->waktu_disetujui = date('Y-m-d H:i:s');
        $model->id_pegawai_penyetuju = Session::getIdPegawai();
        $model->save();

        Yii::$app->session->setFlash('success','Data berhasil disetujui');

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws HttpException
     */
    public function actionSetTolak($id)
    {
        $model = $this->findModel($id);

        if($model->accessSetTolak()==false) {
            throw new HttpException(403, 'Anda tidak meiliki akses ke halaman ini');
        }

        $model->setScenario(KegiatanTahunan::SCENARIO_UPDATE_STATUS);
        $model->setIdKegiatanStatus(KegiatanStatus::TOLAK);
        $model->keterangan_tolak = @Yii::$app->get('keterangan_tolak');
        $model->save();

        Yii::$app->session->setFlash('success','Data berhasil ditolak');

        return $this->redirect(Yii::$app->request->referrer);
    }

        /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws HttpException
     */
    public function actionSetKonsep($id)
    {
        $model = $this->findModel($id);

        if($model->accessSetKonsep()==false) {
            throw new HttpException(403, 'Anda tidak meiliki akses ke halaman ini');
        }

        $model->setScenario(KegiatanTahunan::SCENARIO_UPDATE_STATUS);
        $model->setIdKegiatanStatus(KegiatanStatus::KONSEP);
        $model->save();

        Yii::$app->session->setFlash('success','Data berhasil diperbarui');

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Displays a single KegiatanTahunan model.
     * @param integer $id
     * @return mixed
     */
    public function actionPegawaiView($id)
    {
        return $this->render('pegawai/view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Displays a single KegiatanTahunan model.
     * @param integer $id
     * @return mixed
     */
    public function actionSubordinatView($id)
    {
        return $this->render('subordinat/view', [
            'model' => $this->findModel($id, true),
        ]);
    }

    /**
     * Creates a new KegiatanTahunan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id_pegawai, $id_induk=null,$nomor_skp=1,
         $id_instansi_pegawai = null, $id_instansi_pegawai_skp = null
    ) {
        if (User::isPegawai()) {
            $id_pegawai = User::getIdPegawai();
        }

        $model = new KegiatanTahunan([
            'id_pegawai' => $id_pegawai,
            'tahun' => Session::getTahun(),
            'id_induk' => $id_induk,
            'nomor_skp' => $nomor_skp,
            'id_instansi_pegawai' => $id_instansi_pegawai,
            'id_instansi_pegawai_skp' => $id_instansi_pegawai_skp,
            'id_kegiatan_tahunan_versi' => 1,
        ]);

        if($model->id_instansi_pegawai == null) {
            $model->setIdInstansiPegawai();
        }

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {

            $referrer = $_POST['referrer'];

            //$model->setIdInstansiPegawai();

            if ($model->save()) {

                $model->generateKegiatanBulanan();
                Yii::$app->session->setFlash('success','Data berhasil disimpan.');

                if ($model->id_induk !== null) {
                    return $this->redirect(['kegiatan-tahunan/view','id'=>$model->id_induk]);
                }

                return $this->redirect(['kegiatan-tahunan/view','id'=>$model->id]);
            }

            Yii::$app->session->setFlash('error','Data gagal disimpan. Silahkan periksa kembali isian Anda.');

        }

        return $this->render('create', [
            'model' => $model,
            'referrer'=>$referrer
        ]);

    }

    /**
     * Creates a new KegiatanTahunan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateV2(
        $id_pegawai, $id_induk=null,$nomor_skp=1,
        $id_instansi_pegawai = null, $id_instansi_pegawai_skp = null
    ) {
        if (User::isPegawai()) {
            $id_pegawai = User::getIdPegawai();
        }

        if (Session::isKinerjaPP30Aktif() == false) {
            Yii::$app->session->setFlash('danger', 'Kinerja PP 30/2019 sudah tidak digunakan, silahkan gunakan Permenpan 6 Tahun 2022');
            return $this->redirect(Yii::$app->request->referrer);
        }

        $model = new KegiatanTahunan([
            'id_pegawai' => $id_pegawai,
            'tahun' => Session::getTahun(),
            'id_induk' => $id_induk,
            'nomor_skp' => $nomor_skp,
            'id_instansi_pegawai' => $id_instansi_pegawai,
            'id_instansi_pegawai_skp' => $id_instansi_pegawai_skp,
            'id_kegiatan_tahunan_versi' => 2,
        ]);

        if($model->id_instansi_pegawai == null) {
            $model->setIdInstansiPegawai();
        }

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {

            $referrer = $_POST['referrer'];

            //$model->setIdInstansiPegawai();
            if ($model->id_kegiatan_tahunan_jenis == 2) {
                $model->id_rpjmd_sasaran_indikator = null;
                $model->id_rpjmd_program_indikator = null;
                $model->id_rpjmd_kegiatan_indikator = null;
                $model->id_rpjmd_subkegiatan_indikator = null;
            }

            if ($model->save()) {

                $model->generateKegiatanBulanan();
                $model->generateKegiatanTahunanFungsional();

                Yii::$app->session->setFlash('success','Data berhasil disimpan.');

                if ($model->id_induk !== null) {
                    return $this->redirect(['kegiatan-tahunan/view-v2','id'=>$model->id_induk]);
                }

                return $this->redirect($referrer);
                //return $this->redirect(['kegiatan-tahunan/view','id'=>$model->id]);
            }

            Yii::$app->session->setFlash('error','Data gagal disimpan. Silahkan periksa kembali isian Anda.');

        }

        return $this->render('create-v2', [
            'model' => $model,
            'referrer'=>$referrer
        ]);

    }

    public function actionCreateV3($id_kegiatan_rhk)
    {
        $model = new KegiatanTahunan();
        $model->id_kegiatan_rhk = $id_kegiatan_rhk;
        $model->id_kegiatan_tahunan_versi = 3;
        $model->loadAttributeFromKegiatanRhk();

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post()) && $model->loadAttributeFromKegiatanRhk()) {

            $referrer = $_POST['referrer'];

            $model->setPerspektif();

            if ($model->save()) {
                Yii::$app->session->setFlash('success','Data berhasil disimpan.');
                return $this->redirect($referrer);
            }

            Yii::$app->session->setFlash('error','Data gagal disimpan. Silahkan periksa kembali isian Anda.');
        }

        return $this->render('create-v3', [
            'model' => $model,
            'referrer' => $referrer,
        ]);
    }

    /**
     * Updates an existing KegiatanTahunan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $model->setNomorSkp();

        if ($model->accessUpdate()==false) {
            throw new HttpException(403, 'Anda tidak meiliki akses ke halaman ini');
        }

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {

            $referrer = $_POST['referrer'];

            $model->setIdInstansiPegawai();

            if ($model->save())
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
     * Updates an existing KegiatanTahunan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public function actionUpdateV2($id)
    {
        $model = $this->findModel($id);

        $model->setNomorSkp();
        $model->setArrayFungsional();

        if ($model->accessUpdate()==false) {
            throw new HttpException(403, 'Anda tidak meiliki akses ke halaman ini');
        }

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {

            $referrer = $_POST['referrer'];

            $model->setIdInstansiPegawai();

            if ($model->id_kegiatan_tahunan_jenis == 2) {
                $model->id_rpjmd_sasaran_indikator = null;
                $model->id_rpjmd_program_indikator = null;
                $model->id_rpjmd_kegiatan_indikator = null;
                $model->id_rpjmd_subkegiatan_indikator = null;
            }

            if ($model->save()) {
                $model->updateKegiatanTahunanFungsional();

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
     * @throws NotFoundHttpException
     * @throws HttpException
     */
    public function actionUpdateV3($id)
    {
        $model = $this->findModel($id);
        $model->setArrayPerspektif();

        if ($model->accessUpdate()==false) {
            throw new HttpException(403, 'Anda tidak meiliki akses ke halaman ini');
        }

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {

            $referrer = $_POST['referrer'];

            $model->setPerspektif();

            if ($model->save()) {
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

    public function actionUpdateIdKegiatanTahunanAtasan($id)
    {
        $model = $this->findModel($id);

        if ($model->accessUpdateIdKegiatanTahunanAtasan()==false) {
            throw new HttpException(403, 'Anda tidak meiliki akses ke halaman ini');
        }

        $referrer = Yii::$app->request->referrer;

        if($model->load(Yii::$app->request->post())) {

            $referrer = $_POST['referrer'];

            if ($model->save(false))
            {
                Yii::$app->session->setFlash('success','Data berhasil disimpan.');
                return $this->redirect($referrer);
            }
        }
        return $this->render('update-id-kegiatan-tahunan-atasan',[
            'model' => $model,
            'referrer' => $referrer
        ]);
    }

    public function actionUpdateIdKegiatanTahunanAtasanV2($id)
    {
        $model = $this->findModel($id);

        if ($model->accessUpdateIdKegiatanTahunanAtasan()==false) {
            throw new HttpException(403, 'Anda tidak meiliki akses ke halaman ini');
        }

        $referrer = Yii::$app->request->referrer;

        if($model->load(Yii::$app->request->post())) {

            $referrer = $_POST['referrer'];

            if ($model->save(false))
            {
                Yii::$app->session->setFlash('success','Data berhasil disimpan.');
                return $this->redirect($referrer);
            }
        }
        return $this->render('update-id-kegiatan-tahunan-atasan-v2',[
            'model' => $model,
            'referrer' => $referrer
        ]);
    }

    /**
     * Deletes an existing KegiatanTahunan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws HttpException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if (!$model->accessDelete()) {
            throw new HttpException(403, 'Anda tidak meiliki akses ke halaman ini');
        }

        if ($model->softDelete()) {

            foreach($model->manySub as $sub) {
                $sub->softDelete();
            }

            Yii::$app->session->setFlash('success','Data berhasil dihapus');
        } else {
            Yii::$app->session->setFlash('error','Data gagal dihapus');
        }

        return $this->redirect(Yii::$app->request->referrer);


    }

    /**
     * Finds the KegiatanTahunan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return KegiatanTahunan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $subordinat = false)
    {
        if (($model = KegiatanTahunan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /*
    public function actionRekap()
    {
        $searchModel = new KegiatanTahunanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('rekap', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    */

    public function actionGetList($tanggal = null)
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $id_instansi_pegawai = $parents[0];
                $out = KegiatanTahunan::getListJson($id_instansi_pegawai, false, $tanggal);
                return Json::encode(['output' => $out, 'selected' => null]);
            }
        }
        return Json::encode(['output'=>$out, 'selected'=>'']);
    }

    protected function terapkanAksi($post)
    {
        if(@$post['aksi']==null) {
            Yii::$app->session->setFlash('warning', 'Silahkan pilih aksi yang akan diterapkan ke kegiatan tahunan');
            return $this->redirect(Yii::$app->request->referrer);
        }

        if(@count(@$post['selection'])==0 OR @$post['selection'] == null) {
            Yii::$app->session->setFlash('warning', 'Silahkan pilih kegiatan yang akan diproses terlebih dahulu');
            return $this->redirect(Yii::$app->request->referrer);
        }

        print_r($post['selection']);

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

        $query = KegiatanTahunan::find();
        $query->andWhere(['in', 'id', (array) @$post['selection']]);

        foreach ($query->all() as $model) {
            $model->id_kegiatan_status = $id_kegiatan_status;
            if($model->save()==false) {
                print_r($model->getErrors());
                die();
            }
        }

        Yii::$app->session->setFlash('success', 'Aksi berhasil diterapkan kepada kegiatan tahunan');
        return $this->redirect(Yii::$app->request->referrer);
    }

    protected function kirim($post)
    {
        if (@count($post) === 0 OR $post === null) {
            Yii::$app->session->setFlash('warning', 'Silahkan pilih kegiatan yang akan diproses terlebih dahulu');
            return $this->redirect(Yii::$app->request->referrer);
        }

        $allKegiatanTahunan = KegiatanTahunan::find()->andWhere(['in', 'id', (array) $post])->all();

        foreach ($allKegiatanTahunan as $model) {
            if ($model->target_kuantitas == null or $model->target_kuantitas == 0) {
                Yii::$app->session->setFlash('danger', 'Terdapat target kuantitas kegiatan tahunan yang masih kosong');
                return $this->redirect(Yii::$app->request->referrer);
            }
        }

        foreach ($allKegiatanTahunan as $model) {
            if ($model->target_kuantitas != $model->getSumTargetKegiatanBulanan()) {
                $message = 'Terdapat jumlah target kegiatan bulanan yang belum sesuai dengan
                    jumlah target kegiatan tahunan. Periksa kembali matriks target bulannya dari
                    bulan Januari s.d. Desember harus diisi.';

                if($model->id_kegiatan_tahunan_versi == 2) {
                    $message = 'Terdapat jumlah target kinerja bulanan yang belum sesuai dengan
                    jumlah target kinerja tahunan. Periksa kembali matriks target bulannya dari
                    bulan Juli s.d. Desember harus diisi.';
                }

                Yii::$app->session->setFlash('danger', $message);
                return $this->redirect(Yii::$app->request->referrer);
            }
        }

        foreach ($allKegiatanTahunan as $model) {
            if(($bulan = $model->validateTargetPerBulan()) != 0) {
                Yii::$app->session->setFlash('danger','Harus ada minimal 1 target dari seluruh kegiatan pada bulan '. Helper::getBulanLengkap($bulan));
                return $this->redirect(Yii::$app->request->referrer);
            }
        }


        $i = 0;
        foreach (KegiatanTahunan::find()->andWhere(['in', 'id', (array) $post])->all() as $model) {
            if (!$model->accessSetPeriksa()) {
                continue;
            }
            $model->setScenario(KegiatanTahunan::SCENARIO_UPDATE_STATUS);
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

    protected function konsep($post)
    {
        if (@count($post) === 0 OR $post === null) {
            Yii::$app->session->setFlash('warning', 'Silahkan pilih kegiatan yang akan diproses terlebih dahulu');
            return $this->redirect(Yii::$app->request->referrer);
        }
        $i = 0;
        foreach (KegiatanTahunan::find()->andWhere(['in', 'id', (array) $post])->all() as $model) {
            $model->setScenario(KegiatanTahunan::SCENARIO_UPDATE_STATUS);
            $model->id_kegiatan_status = KegiatanStatus::KONSEP;
            $model->save(false);
            $i++;
        }
        if ($i === 0) {
            Yii::$app->session->setFlash('warning', "Kegiatan yang dipilih sudah dalam status konsep");
        } else {
            Yii::$app->session->setFlash('success', "$i Kegiatan berhasil diubah kembali menjadi konsep");
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionExportPdfSkp($id_pegawai=null)
    {
        if (User::isPegawai()) {
            $id_pegawai = User::getIdPegawai();
        }
        $pegawai = Pegawai::findOne($id_pegawai);

        $content = $this->renderPartial('export-pdf/_pdf-skp',['pegawai' => $pegawai]);

        $cssInline = <<<CSS
        div {
            width: 100%;
        }

        table {
            width: 100%;
        }

CSS;

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8,
            // A4 paper format
            //'format' => Pdf::FORMAT_F4,
            'format' => [215.9,330],
            'defaultFontSize' => '12',
            // portrait orientation
            'marginLeft'=>7,
            'marginRight'=>7,
            'marginTop'=>7,
            'marginBottom'=>7,
            'orientation' => Pdf::ORIENT_LANDSCAPE,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            'cssInline' => $cssInline,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            //'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            //'cssInline' => '.kv-heading-1{font-size:18px}',
             // set mPDF properties on the fly
            'options' => ['title' => 'Export PDF SKP'],
             // call mPDF methods on the fly
            /*'methods' => [
                'SetHeader'=>['Krajee Report Header'],
                'SetFooter'=>['{PAGENO}'],
            ]*/
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }

    public function actionExportExcelSkp($id_pegawai=null)
    {
        if (User::isPegawai()) {
            $id_pegawai = User::getIdPegawai();
        }
        $pegawai = Pegawai::findOne($id_pegawai);

        $PHPExcel = new \PHPExcel();

        $PHPExcel->setActiveSheetIndex();

        $sheet = $PHPExcel->getActiveSheet();

        $sheet->getDefaultStyle()->getAlignment()->setWrapText(true);
        $sheet->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $setBorderArray = [
            'borders' => [
                'allborders' => [
                    'style' => \PHPExcel_Style_Border::BORDER_THIN
                ]
            ]
        ];

        $setBorderOutline = [
          'borders' => [
            'outline' => [
              'style' => \PHPExcel_Style_Border::BORDER_THIN
            ]
          ]
        ];


        $sheet->getColumnDimension('A')->setWidth(1);
        $sheet->getColumnDimension('B')->setWidth(3);
        $sheet->getColumnDimension('C')->setWidth(14);
        $sheet->getColumnDimension('D')->setWidth(31);
        $sheet->getColumnDimension('E')->setWidth(4);
        $sheet->getColumnDimension('F')->setWidth(7);
        $sheet->getColumnDimension('G')->setWidth(8);
        $sheet->getColumnDimension('H')->setWidth(6);
        $sheet->getColumnDimension('I')->setWidth(5);
        $sheet->getColumnDimension('J')->setWidth(8);
        $sheet->getColumnDimension('K')->setWidth(8);
        $sheet->getColumnDimension('L')->setWidth(8);
        $sheet->getColumnDimension('M')->setWidth(9);

        $PHPExcel->getActiveSheet()->setCellValue('A1', 'SASARAN KERJA PEGAWAI');
        $PHPExcel->getActiveSheet()->mergeCells('A1:M1');

        $sheet->getStyle('A1:M1')->getFont()->setBold(true);

        $sheet->getStyle('A1:M1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('B4', 'NO');
        $sheet->setCellValue('C4', 'I. PEJABAT PENILAI');
        $sheet->setCellValue('E4', 'NO');
        $sheet->setCellValue('F4', 'II. PEGAWAI NEGERI SIPIL YANG DINILAI');

        $sheet->setCellValue('B5', '1');
        $sheet->setCellValue('C5', 'Nama');
        $sheet->setCellValue('D5', @$pegawai->atasan->nama);
        $sheet->setCellValue('E5', '1');
        $sheet->setCellValue('F5', 'Nama');
        $sheet->setCellValue('I5', $pegawai->nama);

        $sheet->setCellValue('B6', '2');
        $sheet->setCellValue('C6', 'NIP');
        $sheet->setCellValue('D6', @$pegawai->atasan->nip.' ');
        $sheet->setCellValue('E6', '2');
        $sheet->setCellValue('F6', 'NIP');
        $sheet->setCellValue('I6', $pegawai->nip.' ');

        $sheet->setCellValue('B7', '3');
        $sheet->setCellValue('C7', 'Pangkat/Gol.Ruang');
        $sheet->setCellValue('D7', "");
        $sheet->setCellValue('E7', '3');
        $sheet->setCellValue('F7', 'Pangkat/Gol.Ruang');
        $sheet->setCellValue('I7', "");

        $sheet->setCellValue('B8', '4');
        $sheet->setCellValue('C8', 'Jabatan');
        $sheet->setCellValue('D8', @$pegawai->atasan->nama_jabatan);
        $sheet->setCellValue('E8', '4');
        $sheet->setCellValue('F8', 'Jabatan');
        $sheet->setCellValue('I8', $pegawai->nama_jabatan);

        $sheet->setCellValue('B9', '5');
        $sheet->setCellValue('C9', 'Perangkat Daerah');
        $sheet->setCellValue('D9', @$pegawai->atasan->instansi->nama);
        $sheet->setCellValue('E9', '5');
        $sheet->setCellValue('F9', 'Perangkat Daerah');
        $sheet->setCellValue('I9', @$pegawai->atasan->instansi->nama);

        $sheet->getStyle('B4:M4')->getFont()->setBold(true);

        $sheet->getStyle('B4:M4')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B5:B9')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E5:E9')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('B4:L4')->applyFromArray($setBorderArray);

        $sheet->getStyle('B5:B9')->applyFromArray($setBorderOutline);
        $sheet->getStyle('C5:C9')->applyFromArray($setBorderOutline);
        $sheet->getStyle('D5:D9')->applyFromArray($setBorderOutline);
        $sheet->getStyle('E5:E9')->applyFromArray($setBorderOutline);

        $sheet->getStyle('F5:H9')->applyFromArray($setBorderOutline);
        $sheet->getStyle('I5:M9')->applyFromArray($setBorderOutline);

        $PHPExcel->getActiveSheet()->mergeCells('C4:D4');
        $PHPExcel->getActiveSheet()->mergeCells('F4:M4');

        $PHPExcel->getActiveSheet()->mergeCells('F5:H5');
        $PHPExcel->getActiveSheet()->mergeCells('F6:H6');
        $PHPExcel->getActiveSheet()->mergeCells('F7:H7');
        $PHPExcel->getActiveSheet()->mergeCells('F8:H8');
        $PHPExcel->getActiveSheet()->mergeCells('F9:H9');

        $PHPExcel->getActiveSheet()->mergeCells('I5:M5');
        $PHPExcel->getActiveSheet()->mergeCells('I6:M6');
        $PHPExcel->getActiveSheet()->mergeCells('I7:M7');
        $PHPExcel->getActiveSheet()->mergeCells('I8:M8');
        $PHPExcel->getActiveSheet()->mergeCells('I9:M9');


        $sheet->setCellValue('B10', 'NO');
        $sheet->setCellValue('C10', 'III. KEGIATAN TUGAS JABATAN');
        $sheet->setCellValue('E10', 'AK*');
        $sheet->setCellValue('F10', 'TARGET');
            $sheet->setCellValue('F11', 'KUANTITAS / OUTPUT');
            $sheet->setCellValue('H11', 'KUALITAS/ MUTU');
            $sheet->setCellValue('J11', 'WAKTU');
            $sheet->setCellValue('L11', 'BIAYA **');
        $sheet->setCellValue('M10', 'STATUS');

        $sheet->getStyle('B10:M11')->applyFromArray($setBorderArray);

        $PHPExcel->getActiveSheet()->mergeCells('B10:B11');
        $PHPExcel->getActiveSheet()->mergeCells('C10:D11');
        $PHPExcel->getActiveSheet()->mergeCells('E10:E11');
        $PHPExcel->getActiveSheet()->mergeCells('F10:L10');

        $PHPExcel->getActiveSheet()->mergeCells('F11:G11');
        $PHPExcel->getActiveSheet()->mergeCells('H11:I11');
        $PHPExcel->getActiveSheet()->mergeCells('J11:K11');
        $PHPExcel->getActiveSheet()->mergeCells('M10:M11');

        $sheet->getStyle('B10:M11')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B10:M11')->getFont()->setBold(true);

        $total_ak = 0;
        $row = 12;
        $i=1; foreach(KegiatanTahunan::allIndukByIdPegawai($pegawai->id) as $kegiatanTahunan) {
            $total_ak += floatval($kegiatanTahunan->target_angka_kredit);

            $sheet->setCellValue('B'.$row, $i);
            $sheet->setCellValue('C'.$row, $kegiatanTahunan->nama);
            $sheet->setCellValue('E'.$row, $kegiatanTahunan->target_angka_kredit);
            $sheet->setCellValue('F'.$row, $kegiatanTahunan->target_kuantitas);
            $sheet->setCellValue('G'.$row, $kegiatanTahunan->satuan_kuantitas);
            $sheet->setCellValue('H'.$row, "100");
            $sheet->setCellValue('J'.$row, $kegiatanTahunan->target_waktu);
            $sheet->setCellValue('K'.$row, "Bulan");
            $sheet->setCellValue('L'.$row, $kegiatanTahunan->target_biaya);
            $sheet->setCellValue('M'.$row, @$kegiatanTahunan->kegiatanStatus->nama);

            $PHPExcel->getActiveSheet()->mergeCells('C'.$row.':D'.$row);
            $PHPExcel->getActiveSheet()->mergeCells('H'.$row.':I'.$row);
            $row++;
            $i++;

        }

        $sheet->getStyle('E12:M'.$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('B12:B'.$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('B12:M'.$row)->applyFromArray($setBorderArray);

/*        $row = $row +2;

        $sheet->setCellValue('B'.$row, 'Sumedang,   Januari 2018');

        $row = $row + 1;

        $sheet->setCellValue('B'.$row, 'Pejabat Penilai');
        $sheet->setCellValue('G'.$row, 'Pegawai Negeri Sipil Yang Dinilai');

        $row = $row +5;

        $sheet->setCellValue('B'.$row, @$pegawai->atasan->nama);
        $sheet->setCellValue('G'.$row, $pegawai->nama);
*/

        $sheet->getStyle("A1:M".$row)->getFont()->setSize(10);

        $sheet->getStyle('C' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('B4:M' . $row)->applyFromArray($setBorderOutline);

        $styleArray = [
            'font'  => [
                'color' => ['rgb' => '4D4D4D'],
                'size'  => 10,
            ]];

        $sheet->getStyle('B4:M' . $row)->applyFromArray($styleArray);


        $path = 'exports/';
        $filename = time() . '_ExportSkp.xlsx';
        $objWriter = \PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
        $objWriter->save($path.$filename);
        return Yii::$app->getResponse()->redirect($path.$filename);
    }

    public function actionPerawatan($tahun = '2021', $offset = 0)
    {
        return $this->render('perawatan',[
            'tahun' => $tahun,
            'offset' => $offset
        ]);
    }

    public function exportExcel($queryParams = null)
    {
        return null;
    }

    public function actionMigrasiVersiLama($id)
    {
        $model = $this->findModel($id);
        $model->id_kegiatan_tahunan_versi = 1;
        $model->save();

        Yii::$app->session->setFlash('success', 'Data berhasil dimigrasikan');
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionUpdateIdKegiatanTahunanVersi()
    {
        $query = KegiatanTahunan::find();
        $query->andWhere(['id_kegiatan_tahunan_versi' => 2]);

        foreach($query->all() as $data) {
            $data->updateAttributes([
                'id_kegiatan_tahunan_versi' => 1
            ]);
        }
    }

    /**
     * @param $id
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
            $model->setScenario(KegiatanTahunan::SCENARIO_UPDATE_STATUS);
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

    public function actionUpdateRpjmdIndikator($id)
    {
        $model = $this->findModel($id);

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {

            $referrer = $_POST['referrer'];

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Data berhasil diupdate');
                return $this->redirect($referrer);
            }
        }

        return $this->render('update-rpjmd-indikator', [
            'model' => $model,
            'referrer' => $referrer
        ]);
    }

}
