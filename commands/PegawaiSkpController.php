<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 21/01/2019
 * Time: 21:12
 */

namespace app\commands;


use app\models\InstansiPegawai;
use app\models\Pegawai;
use app\modules\kinerja\models\PegawaiSkp;
use yii\console\Controller;

class PegawaiSkpController extends Controller
{
    public function actionIndex()
    {
        $this->stdout("Hello World");
    }

    public function actionCek()
    {
        $query = Pegawai::find();
        $query->with('manyInstansiPegawai');

        $i=0;
        foreach($query->all() as $pegawai) {
            if(count($pegawai->manyInstansiPegawai)==2) {
                $this->stdout($i++.PHP_EOL);
                $this->stdout("id : $pegawai->nama, nama : $pegawai->nama".PHP_EOL);
            }
        }
    }

    public function actionGenerate2019($id_instansi=null)
    {
        $query = Pegawai::find();

        if($id_instansi!=null) {
            $query->andWhere(['id_instansi'=>$id_instansi]);
        }

        foreach($query->all() as $pegawai) {
            $this->stdout("Nama : $pegawai->nama\n");
            PegawaiSkp::generate2019($pegawai);
        }
    }
}