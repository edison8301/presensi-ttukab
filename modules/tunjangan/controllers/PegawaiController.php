<?php

namespace app\modules\tunjangan\controllers;

use app\components\Session;
use app\models\Pengaturan;
use app\models\PengaturanBerlaku;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Yii;
use app\components\Helper;
use app\models\User;
use app\modules\tukin\models\Pegawai;
use app\modules\tunjangan\models\FilterTunjanganForm;
use Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

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
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['view', 'view-v3', 'update-ip-asn'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['web-view-tunjangan'],
                        'allow' => true,
                        'roles' => ['*','@','?'],
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
     * Displays a single Pegawai model.
     * @param integer $id
     * @param bool $export
     * @return mixed
     * @throws NotFoundHttpException
     * @throws NotFoundHttpException
     */
    public function actionView($id=null, $export = false)
    {
        User::redirectDefaultPassword();

        if (User::isPegawai()) {
            $id = User::getIdPegawai();
        }

        $filter = new FilterTunjanganForm($this->findModel($id));

        $bulan = date('n');
        if(date('j') <= 10 AND $bulan != 1) {
            $bulan = $bulan - 1;
            if(strlen($bulan) == 1) {
                $bulan = '0'.$bulan;
            }
        }

        $filter->bulan = $bulan;
        $filter->tahun = Session::getTahun();

        $filter->load(Yii::$app->request->queryParams);

        $rekapTunjangan = $filter->pegawai->findOrCreatePegawaiRekapTunjangan($filter->bulan);
        $pegawai = $filter->pegawai;

        $isTampilNilaiTpp = true;

        $nilai = Pengaturan::findNilai([
            'id'=>Pengaturan::ID_TAMPILKAN_NILAI_RUPIAH_TUNJANGAN_PADA_USER_PEGAWAI
        ]);

        if($nilai != 1 AND User::isPegawai()) {
            $isTampilNilaiTpp = false;
        }

        //$pegawai->getPotonganBulan($filter->bulan);

        return $this->render('view', [
            'filter' => $filter,
            'model' => $pegawai,
            'isTampilNilaiTpp' => $isTampilNilaiTpp
        ]);
    }

    /**
     * Displays a single Pegawai model.
     * @param integer $id
     * @param bool $export
     * @return mixed
     * @throws NotFoundHttpException
     * @throws NotFoundHttpException
     */
    public function actionViewV3($id=null, $export = false)
    {
        User::redirectDefaultPassword();

        if (User::isPegawai()) {
            $id = User::getIdPegawai();
        }

        $filter = new FilterTunjanganForm($this->findModel($id));

        $bulan = date('n');
        if(date('j') <= 10 AND $bulan != 1) {
            $bulan = $bulan - 1;
            if(strlen($bulan) == 1) {
                $bulan = '0'.$bulan;
            }
        }

        $filter->bulan = $bulan;
        $filter->tahun = Session::getTahun();

        $filter->load(Yii::$app->request->queryParams);

        if ($filter->bulan == null) {
            $filter->bulan = $bulan;
        }

        $rekapTunjangan = $filter->pegawai->findOrCreatePegawaiRekapTunjangan($filter->bulan);
        $pegawai = $filter->pegawai;

        $isTampilNilaiTpp = true;

        $nilai = Pengaturan::findNilai([
            'id'=>Pengaturan::ID_TAMPILKAN_NILAI_RUPIAH_TUNJANGAN_PADA_USER_PEGAWAI
        ]);

        if($nilai != 1 AND User::isPegawai()) {
            $isTampilNilaiTpp = false;
        }

        //$pegawai->getPotonganBulan($filter->bulan);

        return $this->render('view-v3', [
            'filter' => $filter,
            'model' => $pegawai,
            'isTampilNilaiTpp' => $isTampilNilaiTpp
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Pegawai::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionWebViewTunjangan($nip)
    {
        $this->layout = '/webview';

        $pegawai = Pegawai::findOne([
            'nip' => $nip
        ]);

        if($pegawai === null) {
            return $this->render('//site/web-view-kosong');
        }

        $filter = new FilterTunjanganForm($pegawai);

        $bulan = date('n');
        if(date('j') <= 10 AND $bulan != 1) {
            $bulan = $bulan - 1;
            if(strlen($bulan) == 1) {
                $bulan = '0'.$bulan;
            }
        }

        $filter->bulan = $bulan;
        $filter->tahun = Session::getTahun();

        $filter->load(Yii::$app->request->queryParams);

        $isTampilNilaiTpp = true;

        $nilai = Pengaturan::findNilai([
            'id'=>Pengaturan::ID_TAMPILKAN_NILAI_RUPIAH_TUNJANGAN_PADA_USER_PEGAWAI
        ]);

        if($nilai != 1 AND User::isPegawai()) {
            $isTampilNilaiTpp = false;
        }

        return $this->render('web-view-tunjangan', [
            'filter' => $filter,
            'model' => $pegawai,
            'isTampilNilaiTpp' => $isTampilNilaiTpp
        ]);
    }

    public function actionUpdateIpAsn($id, $bulan)
    {
        if (Pegawai::accessRefreshIpAsn(['bulan' => $bulan]) == false) {
            throw new ForbiddenHttpException('Anda tidak diperbolehkan mengakses aksi ini');
        }
        
        $model = $this->findModel($id);

        $model->updateRekapPegawaiBulanIpAsn([
            'bulan' => $bulan,
            'tahun' => Session::getTahun(), 
        ]);

        Yii::$app->session->setFlash('success', 'Refresh Skor IP ASN berhasil dilakukan');
        return $this->redirect(Yii::$app->request->referrer);
    }
}
