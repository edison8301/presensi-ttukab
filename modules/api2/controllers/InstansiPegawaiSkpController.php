<?php


namespace app\modules\api2\controllers;


use app\modules\api2\models\Pegawai;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\modules\api2\models\InstansiPegawaiSkp;

class InstansiPegawaiSkpController extends Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => \yii\filters\ContentNegotiator::class,
                'formats' => [
                    'application/json' => \yii\web\Response::FORMAT_JSON,
                ],
            ],
        ];
    }

    public function actionListJabatan($nip, $tahun=null)
    {
        $pegawai = $this->findPegawaiByNip($nip);
        $data = InstansiPegawaiSkp::restListJabatan([
            'id_pegawai' => @$pegawai->id,
            'tahun' => ($tahun === null) ? date('Y') : $tahun
        ]);

        return $data;
    }

    protected function findPegawaiByNip($nip)
    {
        $model = Pegawai::findOne(['nip' => $nip]);
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
