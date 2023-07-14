<?php

namespace app\modules\tandatangan\controllers;

use app\components\Session;
use app\models\User;
use app\modules\tandatangan\models\Berkas;
use app\modules\tandatangan\models\BerkasSearch;
use app\modules\tandatangan\models\BerkasStatus;
use Yii;
use yii\web\Controller;

/**
 * Dashboard controller for the `tandatangan` module
 */
class DashboardController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        clearstatcache();
        $searchModel = new BerkasSearch();
        $searchModel->id_berkas_status = BerkasStatus::TANDATANGAN;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $id_instansi = null;

        if(User::isInstansi()) {
            $id_instansi = User::getIdInstansi();
        }

        $jumlahBelumTandatangan = Berkas::find()
            ->andWhere(['tahun' => Session::getTahun()])
            ->andWhere(['id_berkas_status' => BerkasStatus::VERIFIKASI])
            ->andFilterWhere(['id_instansi' => $id_instansi])
            ->count();

        $jumlahSudahTandatangan = Berkas::find()
            ->andWhere(['tahun' => Session::getTahun()])
            ->andWhere(['id_berkas_status' => BerkasStatus::SELESAI])
            ->andFilterWhere(['id_instansi' => $id_instansi])
            ->count();

        $jumlahTotalTandatangan = Berkas::find()
            ->andWhere(['tahun' => Session::getTahun()])
            ->andFilterWhere(['id_instansi' => $id_instansi])
            ->count();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'jumlahBelumTandatangan' => $jumlahBelumTandatangan,
            'jumlahSudahTandatangan' => $jumlahSudahTandatangan,
            'jumlahTotalTandatangan' => $jumlahTotalTandatangan,
        ]);
    }
}
