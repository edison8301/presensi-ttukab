<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 01/01/2019
 * Time: 9:17
 */

namespace app\commands;


use app\models\Instansi;
use app\models\Pegawai;
use app\modules\absensi\models\PegawaiRekapAbsensi;
use yii\console\Controller;
use yii\helpers\Console;

class PegawaiRekapAbsensiController extends Controller
{
    public $id_pegawai;
    public $id_instansi;
    public $bypass;
    public $fix;

    public function options($actionID)
    {
        $options = parent::options($actionID);
        $options = array_merge($options,['id_pegawai']);
        $options = array_merge($options,['id_instansi']);
        $options = array_merge($options,['bypass']);
        $options = array_merge($options,['fix']);

        return $options;
    }

    public function actionIndex($bulan,$tahun)
    {
        $this->stdout("Bulan $bulan Tahun $tahun $this->id_pegawai $this->id_instansi");
    }

    public function actionMaintenanceDouble($tahun, $bulan, $proses=0)
    {
        $query = Pegawai::find();

        $query->andFilterWhere([
            'id'=>$this->id_pegawai,
            'id_instansi'=>$this->id_instansi
        ]);

        $done = 0;
        $total = $query->count();

        foreach($query->all() as $pegawai)
        {

            $queryRekap = PegawaiRekapAbsensi::find();
            $queryRekap->andWhere([
               'bulan'=>$bulan,
               'tahun'=>$tahun,
               'id_pegawai'=>$pegawai->id
            ]);

            if($queryRekap->count() > 1) {

                $this->stdout("id : $pegawai->id - $pegawai->nama - $pegawai->nip");

                if($proses==1) {
                    PegawaiRekapAbsensi::deleteAll('bulan = :bulan AND tahun = :tahun AND id_pegawai = :id_pegawai',[
                        ':bulan'=>$bulan,
                        ':tahun'=>$tahun,
                        ':id_pegawai'=>$pegawai->id
                    ]);

                    $pegawai->getPotonganBulan($bulan,$tahun);
                    $pegawai->updatePegawaiRekapAbsensi($bulan,$tahun);
                }
            }

            $done++;

            $this->stdout("Progres $done / $total \n");
        }

    }

    public function actionDeleteAll($bulan,$tahun)
    {
        PegawaiRekapAbsensi::deleteAll('bulan = :bulan AND tahun = :tahun',[
            ':bulan'=>$bulan,
            ':tahun'=>$tahun
        ]);
    }

    public function actionDeleteInstansi($bulan,$tahun,$id_instansi)
    {
        PegawaiRekapAbsensi::deleteAll('bulan = :bulan AND tahun = :tahun AND id_instansi = :id_instansi',[
            ':bulan'=>$bulan,
            ':tahun'=>$tahun,
            ':id_instansi'=>$id_instansi
        ]);

    }

    public function actionDeletePegawai($bulan,$tahun,$id_pegawai)
    {
        PegawaiRekapAbsensi::deleteAll('bulan = :bulan AND tahun = :tahun AND id_pegawai = :id_pegawai',[
            ':bulan'=>$bulan,
            ':tahun'=>$tahun,
            ':id_pegawai'=>$id_pegawai
        ]);

    }

    public function actionMonitorInstansi($bulan,$tahun)
    {
        $query = Instansi::find();

        $totalPegawai = 0;
        $totalRekap = 0;
        foreach($query->all() as $instansi) {
            $jumlahPegawai = $instansi->countPegawai();
            $totalPegawai = $totalPegawai + $jumlahPegawai;

            $queryRekap = PegawaiRekapAbsensi::find();
            $queryRekap->andWhere([
               'id_instansi'=>$instansi->id,
               'bulan'=>$bulan,
               'tahun'=>$tahun
            ]);

            $jumlahRekap = $queryRekap->count();
            $totalRekap = $totalRekap + $jumlahPegawai;

            if($jumlahPegawai!=$jumlahRekap) {
                $this->stdout("$instansi->id - $instansi->nama : Jumlah : Pegawai = $jumlahPegawai, Rekap = $jumlahRekap \n");
            }
        }

        $this->stdout("Total Pegawai : $totalPegawai - Total Rekap : $totalRekap \n");
    }

    public function actionMonitorPegawai($bulan,$tahun)
    {
        $query = Pegawai::find();

        $query->andFilterWhere([
            'id_pegawai_status'=>1,
            'id'=>$this->id_pegawai,
            'id_instansi'=>$this->id_instansi
        ]);

        $done = 0;
        $total = $query->count();

        foreach($query->all() as $pegawai)
        {
            $queryRekap = PegawaiRekapAbsensi::find();
            $queryRekap->andWhere([
               'id_pegawai'=>$pegawai->id,
               'bulan'=>$bulan,
               'tahun'=>$tahun
            ]);

            $count = $queryRekap->count();

            if($count!=1) {
                $this->stdout("$pegawai->id - $pegawai->nama - $pegawai->nip : Jumlah Rekap = $count \n");
                if($this->fix==true) {
                    PegawaiRekapAbsensi::deleteAll('bulan = :bulan AND tahun = :tahun AND id_pegawai = :id_pegawai',[
                        ':bulan'=>$bulan,
                        ':tahun'=>$tahun,
                        ':id_pegawai'=>$pegawai->id
                    ]);

                    $pegawai->findOrCreatePegawaiRekapAbsensi($bulan,$tahun);
                }
            }

            if($count==1) {
                $rekap = $queryRekap->one();

                $id_instansi_berlaku = $pegawai->getIdInstansiBerlaku($bulan,$tahun);

                if($rekap->id_instansi != $id_instansi_berlaku) {
                    $this->stdout("$pegawai->id - $pegawai->nama - $pegawai->nip : Id Instansi : Pegawai = $id_instansi_berlaku, Rekap = $rekap->id_instansi \n");
                    if($this->fix==true) {
                        $rekap->id_instansi = $id_instansi_berlaku;
                        $rekap->save(false);
                    }
                }
            }

            $done++;

            if($done%100==0) {
                $this->stdout("Step : $done/$total \n");
            }
        }
    }

    public function actionMonitorRekap($bulan,$tahun)
    {
        $query = PegawaiRekapAbsensi::find();
        $query->andFilterWhere([
           'bulan'=>$bulan,
           'tahun'=>$tahun,
           'id_pegawai'=>$this->id_pegawai,
           'id_instansi'=>$this->id_instansi
        ]);

        foreach ($query->all() as $rekap) {
            if($rekap->id_instansi != @$rekap->pegawai->id_instansi) {
                $this->stdout($rekap->pegawai->id." - ".$rekap->pegawai->nama." - ".@$rekap->pegawai->nip." : ".@$rekap->id_instansi." vs ".@$rekap->pegawai->id_instansi."\n");

            }
        }
    }
}
