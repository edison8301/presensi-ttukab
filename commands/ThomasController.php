<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 15/01/2019
 * Time: 22:02
 */

namespace app\commands;


use app\models\Instansi;
use app\models\Pegawai;
use app\modules\absensi\models\PegawaiRekapAbsensi;
use app\modules\kinerja\models\KegiatanTahunan;
use Yii;
use yii\console\Controller;
use yii2mod\query\ArrayQuery;

class ThomasController extends Controller
{
    public function actionIndex()
    {
        $this->stdout("Hello World");
    }

    public function actionCacheFlush()
    {
        Yii::$app->cache->flush();
    }

    public function actionPerbandinganJumlah()
    {
        $query1 = Pegawai::find();
        $query1->aktif();
        $query1->andWhere(['pegawai.id_instansi'=>42]);



        $i=1;
        foreach($query1->all() as $pegawai1) {
            $this->stdout("$i. Nama : $pegawai1->nama \n");
            $i++;
        }

        $jumlah1 = $query1->count();
        $this->stdout("Jumlah 1: $jumlah1 \n");

        $this->stdout("\n\n");

        $query2 = Pegawai::find();
        $query2->joinWith(['allInstansiPegawai']);
        $query2->andWhere(['pegawai.id_instansi'=>42]);

        $i=1;
        foreach($query2->all() as $pegawai2) {
            $this->stdout("$i. Nama : $pegawai2->nama \n");
            $i++;
        }

        $jumlah2= $query2->count();
        $this->stdout("Jumlah 2: $jumlah2 \n");

        foreach($query2->all() as $pegawai) {
            $query3 = new ArrayQuery();
            $query3->from($query1->all());
            $query3->andWhere(['id'=>$pegawai->id]);
            $model = $query3->one();
            if($model===null) {
                $this->stdout("ID Pegwai : $pegawai->id; Nama Pegawai : $pegawai->nama \n");
            }
        }

    }

    public function actionJumlah()
    {
        $query1 = Pegawai::find();
        $query1->aktif();
        $query1->andWhere(['id_instansi'=>1]);

        $jumlah1 = $query1->count();
        $this->stdout("Jumlah Pegawai : $jumlah1 \n");

        $query2 = PegawaiRekapAbsensi::find();
        $query2->andWhere([
            'id_instansi'=>1,
            'bulan'=>1,
            'tahun'=>2019
        ]);

        $jumlah2 = $query2->count();
        $this->stdout("Jumlah Rekap Pegawai : $jumlah2 \n");



        foreach ($query1->all() as $pegawai)
        {
            $query2 = PegawaiRekapAbsensi::find();
            $query2->andWhere([
                'id_pegawai'=>$pegawai->id,
                'id_instansi'=>1,
                'bulan'=>1,
                'tahun'=>2019
            ]);

            if($query2->one()===null) {
                $this->stdout("$pegawai->id $pegawai->nama $pegawai->id_instansi \n");
            }
        }
    }

    public function actionJabatanInstansi()
    {
        $query = Pegawai::find();
        $jumlah = 0;
        foreach($query->all() as $pegawai) {
            if($pegawai->jabatan !== null AND @$pegawai->jabatan->id_instansi != $pegawai->id_instansi) {
                $this->stdout("$pegawai->nama - $pegawai->nip \n");
                $jumlah++;
            }
        }

        $this->stdout("Jumlah : $jumlah");
    }

    public function actionJabatanNull()
    {
        $query = Pegawai::find();
        $query->andWhere('id_jabatan IS NULL');
        $jumlah = $query->count();

        $this->stdout("Jumlah Jabatan dengan id_jabatan null : $jumlah");
    }

    public function actionDinasNonAktif()
    {
        $query = Pegawai::find();
        $query->andWhere(['id_instansi'=>164]);
        $jumlah = $query->count();

        $this->stdout("Jumlah Pegawai Dinas Non-Aktif : $jumlah \n");

        $no = 1;
        foreach($query->all() as $data) {
            $data->id_jabatan = null;
            $data->save(false);

            $this->stdout("$no : $data->id - $data->nama - $data->nip \n");
            $no++;
        }
    }

    public function actionKegiatanTahunanHapus($proses=false)
    {
        $query = KegiatanTahunan::parentFind();
        $query->andWhere('status_hapus = 1');
        $query->andWhere('id_induk IS NULL');
        $query->with('manySub');

        $this->stdout("Jumlah Kegiatan Tahunan Hapus : ".$query->count().PHP_EOL);

        $i=0;
        if($proses==true) {
            $this->stdout("Proses Hapus".PHP_EOL);
            foreach ($query->all() as $data) {
                foreach ($data->manySub as $sub) {
                    if ($sub->status_hapus == 0) {
                        $this->stdout($i++ . PHP_EOL);
                        $sub->softDelete();
                    }
                }
            }
        }

    }
}