<?php

namespace app\modules\kinerja\controllers;

use app\models\KegiatanTahunanVersi;
use app\modules\kinerja\models\KegiatanBulanan;
use app\modules\kinerja\models\KegiatanStatus;
use app\modules\kinerja\models\KegiatanTahunan;
use Yii;
use app\components\Session;
use app\modules\tukin\models\Pegawai;
use app\models\PegawaiSearch;
use app\models\User;
use app\modules\kinerja\models\KegiatanBulananSearch;
use app\modules\kinerja\models\RekapBulananForm;
use app\modules\kinerja\models\RekapHarianForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class PegawaiController extends Controller
{
    /**
     * @return string|\yii\web\Response
     */
    public function actionIndex()
    {
        if (!User::isAdmin()) {
            return $this->redirect(['pegawai/subordinat'], 302); //moved permanently
        }
    	$searchModel = new PegawaiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexV2()
    {
        if (!User::isAdmin()) {
            return $this->redirect(['pegawai/subordinat'], 302); //moved permanently
        }
    	$searchModel = new PegawaiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index-v2', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return string
     */
    public function actionSubordinat()
    {
        $searchModel = new PegawaiSearch(['scenario' => PegawaiSearch::SCENARIO_ATASAN]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id = null)
    {
        User::redirectDefaultPassword();

        if (User::isPegawai()) {
            $id = User::getIdPegawai();
        }

        $searchModel = new KegiatanBulananSearch();

        $model = $this->findModel($id);
        $searchModel->id_pegawai = $model->id;

        $bulan = date('n');
        if(date('j') <= 10 AND $bulan != 1) {
            $bulan = $bulan - 1;
        }

        $searchModel->bulan = $bulan;

        $queryKegiatanBulan = $searchModel->querySearch(Yii::$app->request->queryParams);

        return $this->render('view', [
            'model' => $model,
            'searchModel' => $searchModel,
            'queryKegiatanBulan' => $queryKegiatanBulan
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionViewV2($id = null)
    {
        User::redirectDefaultPassword();

        if (User::isPegawai()) {
            $id = User::getIdPegawai();
        }

        $searchModel = new KegiatanBulananSearch();

        $model = $this->findModel($id);
        $searchModel->id_pegawai = $model->id;

        $bulan = date('n');
        if(date('j') <= 10 AND $bulan != 1) {
            $bulan = $bulan - 1;
        }

        $searchModel->bulan = $bulan;

        $searchModel->load(Yii::$app->request->queryParams);

        $allKegiatanBulanan = KegiatanBulanan::findAll([
            'joinWith' => ['kegiatanTahunan', 'instansiPegawai'],
            'kegiatan_tahunan.id_pegawai' => $model->id,
            'kegiatan_tahunan.tahun' => Session::getTahun(),
            'kegiatan_tahunan.id_kegiatan_status' => 1,
            'kegiatan_tahunan.status_hapus' => 0,
            'kegiatan_tahunan.id_kegiatan_tahunan_versi' => 2,
            'bulan' => $searchModel->bulan,
            'target_is_not_null' => true,
            'kegiatan_tahunan.status_plt'=>'0',
        ]);

        return $this->render('view-v2', [
            'model' => $model,
            'searchModel' => $searchModel,
            'allKegiatanBulanan' => $allKegiatanBulanan
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionViewV3($id = null)
    {
        User::redirectDefaultPassword();

        if (User::isPegawai()) {
            $id = User::getIdPegawai();
        }

        $searchModel = new KegiatanBulananSearch();

        $model = $this->findModel($id);
        $searchModel->id_pegawai = $model->id;

        $bulan = date('n');
        if(date('j') <= 10 AND $bulan != 1) {
            $bulan = $bulan - 1;
        }

        $searchModel->bulan = $bulan;

        $searchModel->load(Yii::$app->request->queryParams);

        $allKegiatanBulanan = KegiatanBulanan::findAll([
            'joinWith' => ['kegiatanTahunan', 'instansiPegawai'],
            'kegiatan_tahunan.id_pegawai' => $model->id,
            'kegiatan_tahunan.tahun' => Session::getTahun(),
            'kegiatan_tahunan.id_kegiatan_status' => 1,
            'kegiatan_tahunan.status_hapus' => 0,
            'kegiatan_tahunan.id_kegiatan_tahunan_versi' => 3,
            'bulan' => $searchModel->bulan,
            'target_is_not_null' => true,
            'kegiatan_tahunan.status_plt'=>'0',
        ]);

        return $this->render('view-v3', [
            'model' => $model,
            'searchModel' => $searchModel,
            'allKegiatanBulanan' => $allKegiatanBulanan
        ]);
    }

    public function actionViewTahunan($id = null)
    {
        User::redirectDefaultPassword();

        if (User::isPegawai()) {
            $id = User::getIdPegawai();
        }

        $model = $this->findModel($id);

        $query = KegiatanTahunan::find();
        $query->andWhere([
            'id_pegawai' => $model->id,
            'id_kegiatan_tahunan_versi' => KegiatanTahunanVersi::PP_30_TAHUN_2O19,
            'id_kegiatan_status' => KegiatanStatus::SETUJU,
            'tahun' => Session::getTahun(),
        ]);

        $allKegiatanTahunan = $query->all();

        return $this->render('view-tahunan', [
            'model' => $model,
            'allKegiatanTahunan' => $allKegiatanTahunan,
        ]);
    }

    /**
     * @param null $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionViewRekapKegiatanHarian($id=null)
    {
        User::redirectDefaultPassword();

        if(User::isPegawai()) {
            $id = User::getIdPegawai();
        }

        $model = $this->findModel($id);

        $rekapHarianForm = new RekapHarianForm(['bulan' => date('m')]);
        $rekapHarianForm->load(Yii::$app->request->queryParams);

        return $this->render('view-rekap-kegiatan-harian', [
            'pegawai' => $model,
            'rekapHarianForm'=>$rekapHarianForm
        ]);
    }

    /**
     * @param null $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionViewRekapKegiatanHarianV2($id=null)
    {
        User::redirectDefaultPassword();

        if(User::isPegawai()) {
            $id = User::getIdPegawai();
        }

        $model = $this->findModel($id);

        $rekapHarianForm = new RekapHarianForm(['bulan' => date('m')]);
        $rekapHarianForm->load(Yii::$app->request->queryParams);

        return $this->render('view-rekap-kegiatan-harian-v2', [
            'pegawai' => $model,
            'rekapHarianForm'=>$rekapHarianForm
        ]);
    }

    public function actionViewRekapKegiatanHarianV3($id=null)
    {
        User::redirectDefaultPassword();

        if(User::isPegawai()) {
            $id = User::getIdPegawai();
        }

        $model = $this->findModel($id);

        $rekapHarianForm = new RekapHarianForm(['bulan' => date('m')]);
        $rekapHarianForm->load(Yii::$app->request->queryParams);

        return $this->render('view-rekap-kegiatan-harian-v3', [
            'pegawai' => $model,
            'rekapHarianForm'=>$rekapHarianForm
        ]);
    }

    public function actionViewRekapKegiatanHarianV4($id=null)
    {
        User::redirectDefaultPassword();

        if(User::isPegawai()) {
            $id = User::getIdPegawai();
        }

        $model = $this->findModel($id);

        $rekapHarianForm = new RekapHarianForm(['bulan' => date('m')]);
        $rekapHarianForm->load(Yii::$app->request->queryParams);

        return $this->render('view-rekap-kegiatan-harian-v4', [
            'pegawai' => $model,
            'rekapHarianForm'=>$rekapHarianForm
        ]);
    }

    /**
     * @return string
     */
    public function actionRekapKegiatanHarian()
    {
        $rekapHarianForm = new RekapHarianForm(['bulan' => date('m')]);
        $rekapHarianForm->load(Yii::$app->request->queryParams);
        $rekapHarianForm->loadDefaultAttributes();
        return $this->render('rekap-kegiatan-harian', [
            'pegawai' => $rekapHarianForm->pegawai,
            'rekapHarianForm' => $rekapHarianForm
        ]);
    }

    /**
     * @param $id
     * @return Pegawai|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Pegawai::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function refreshRekap()
    {
        $pegawai = $this->findModel(User::getIdPegawai());
        for ($i = 1; $i <= 12; $i++) {
            $pegawai->updatePegawaiRekapKinerja($i);
        }
    }

    /**
     * @param null $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionViewRekapKegiatanBulanan($id=null)
    {
        User::redirectDefaultPassword();

        if(User::isPegawai()) {
            $id = User::getIdPegawai();
        }

        $model = $this->findModel($id);

        return $this->render('view-rekap-kegiatan-bulanan', [
            'pegawai' => $model,
        ]);
    }

    public function actionViewRekapKegiatanBulananV2($id=null)
    {
        User::redirectDefaultPassword();

        if(User::isPegawai()) {
            $id = User::getIdPegawai();
        }

        $model = $this->findModel($id);

        return $this->render('view-rekap-kegiatan-bulanan-v2', [
            'pegawai' => $model,
        ]);
    }

    public function actionViewRekapKegiatanBulananV3($id=null)
    {
        User::redirectDefaultPassword();

        if(User::isPegawai()) {
            $id = User::getIdPegawai();
        }

        $model = $this->findModel($id);

        return $this->render('view-rekap-kegiatan-bulanan-v3', [
            'model' => $model,
        ]);
    }

    public function actionRefreshInstansiPegawaiSkp($id=null) {

    }

    public function actionWebViewKinerja($nip)
    {
        $this->layout = '/webview';
        $nip = str_replace(' ','', $nip);

        $pegawai = Pegawai::findOne([
            'nip' => $nip
        ]);

        return $this->render('web-view-kinerja', [
            'pegawai' => $pegawai,
        ]);
    }

    public function actionWebViewKinerjaBulanan($nip, $back=false)
    {
        $this->layout = '/webview';

        $searchModel = new KegiatanBulananSearch();

        $nip = str_replace(' ','', $nip);

        $pegawai = Pegawai::findOne([
            'nip' => $nip
        ]);

        if($pegawai === null) {
            return $this->render('//site/web-view-kosong');
        }

        $searchModel->id_pegawai = $pegawai->id;

        $bulan = date('n');
        if(date('j') <= 10 AND $bulan != 1) {
            $bulan = $bulan - 1;
        }

        $searchModel->bulan = $bulan;

        $searchModel->load(Yii::$app->request->queryParams);

        $allKegiatanBulanan = KegiatanBulanan::findAll([
            'joinWith' => ['kegiatanTahunan'],
            'kegiatan_tahunan.id_pegawai' => $pegawai->id,
            'kegiatan_tahunan.tahun' => Session::getTahun(),
            'kegiatan_tahunan.id_kegiatan_status' => 1,
            'kegiatan_tahunan.status_hapus' => 0,
            'kegiatan_tahunan.id_kegiatan_tahunan_versi' => 2,
            'bulan' => $searchModel->bulan,
            'target_is_not_null' => true,
            'kegiatan_tahunan.status_plt'=>'0',
        ]);

        return $this->render('web-view-kinerja-bulanan', [
            'pegawai' => $pegawai,
            'searchModel' => $searchModel,
            'allKegiatanBulanan' => $allKegiatanBulanan,
            'back' => $back
        ]);
    }
}
