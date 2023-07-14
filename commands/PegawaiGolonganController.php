<?php


namespace app\commands;


use app\models\Pegawai;
use yii\console\Controller;

class PegawaiGolonganController extends Controller
{
    public function actionIndex()
    {
        $query = Pegawai::find();
        foreach($query->all() as $pegawai) {
            if($pegawai->getCountPegawaiGolongan() > 1) {
                $this->stdout('nama: '.$pegawai->nama.', nip: '. $pegawai->nip."\n");
            }
        }
    }
}
