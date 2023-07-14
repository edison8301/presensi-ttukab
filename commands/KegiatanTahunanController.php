<?php


namespace app\commands;

use app\models\KegiatanTahunanFungsional;
use app\modules\kinerja\models\KegiatanBulanan;
use app\modules\kinerja\models\KegiatanTahunan;
use yii\console\Controller;
use yii\helpers\Console;

class KegiatanTahunanController extends Controller
{
    public function actionIndex($proses=false, $offset=0, $limit=null) {
        $query = KegiatanTahunan::find();
        $query->andWhere(['kegiatan_tahunan.tahun'=>2020]);

        if($limit!=null) {
            $query->limit($limit);
        }

        $query->offset($offset);

        $i=1;
        foreach($query->all() as $data) {
            if($data->instansiPegawaiSkp === null) {
                $this->stdout($i.' - '.$data->pegawai->namaNip.' - id: '.$data->id.' - '.$data->nama."\n");
                if($proses == true) {
                    KegiatanBulanan::deleteAll([
                        'id_kegiatan_tahunan' => $data->id
                    ]);
                    $data->delete();
                }
            }
            $i++;
        }
    }

    public function actionIndexBaru($proses=false, $offset=0, $limit=null) {
        $query = KegiatanTahunan::find();
        $query->andWhere(['kegiatan_tahunan.tahun'=>2020]);

        if($limit!=null) {
            $query->limit($limit);
        }

        $query->offset($offset);

        $i=1;
        foreach($query->all() as $data) {
            if($data->instansiPegawai === null) {
                $this->stdout($i.' - '.$data->pegawai->namaNip.' - id: '.$data->id.' - '.$data->nama."\n");
                if($proses == true) {
                    KegiatanBulanan::deleteAll([
                        'id_kegiatan_tahunan' => $data->id
                    ]);
                    $data->delete();
                }
            }
            $i++;
        }
    }

    public function actionUpdateStatusPlt()
    {
        $query = KegiatanTahunan::find();
        $query->joinWith(['instansiPegawai']);
        $query->andWhere(['instansi_pegawai.status_plt'=>1]);

        $allKegiatanTahunan = $query->all();

        $done = 0;
        $total = count($allKegiatanTahunan);

        Console::startProgress($done,$total);

        foreach($allKegiatanTahunan as $kegiatanTahunan) {
            $kegiatanTahunan->updateAttributes([
                'status_plt' => 1
            ]);
            Console::updateProgress($done++,$total);
        }

        Console::endProgress();

    }

    public function actionMigrasiDataFungsional() {
        $query = KegiatanTahunan::find();
        $query->andWhere(['id_kegiatan_tahunan_versi' => 2]);

        $done = 0;
        $total = count($query->all());

        Console::startProgress($done,$total);

        foreach($query->all() as $kegiatanTahunan) {
            if($kegiatanTahunan->butir_kegiatan != null
                OR $kegiatanTahunan->output != null
                OR $kegiatanTahunan->target_angka_kredit != null
            ) {
                $model = new KegiatanTahunanFungsional();
                $model->id_kegiatan_tahunan = $kegiatanTahunan->id;
                $model->butir_kegiatan = $kegiatanTahunan->butir_kegiatan;
                $model->output = $kegiatanTahunan->output;
                $model->angka_kredit = $kegiatanTahunan->target_angka_kredit;
                if(!$model->save()) {
                    print_r($model->getErrors());die;
                }
            }
            Console::updateProgress($done++,$total);
        }

        Console::endProgress();
    }

}
