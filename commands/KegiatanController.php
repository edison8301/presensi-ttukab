<?php

namespace app\commands;

use Yii;
use app\modules\kinerja\models\KegiatanHarianConsole as KegiatanHarian;
use app\modules\kinerja\models\KegiatanTahunan;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * ..
 */
class KegiatanController extends Controller
{
    public function actionHarianHapus()
    {
        $data = KegiatanHarian::find()
            ->andWhere(['tanggal' => date('Y-m-d'), 'id_pegawai' => 135])
            ->all();
        foreach ($data as $kegiatan) {
            $kegiatan->softDelete();
        }
    }

    public function actionHarianInstansiPegawai()
    {
        $tahun = date('Y');
        $query = KegiatanHarian::find()
            ->with([
                'pegawai.allInstansiPegawai' => function (\yii\db\ActiveQuery $query) use ($tahun) {
                    $query->andWhere(['tahun' => $tahun]);
                },
                'kegiatanTahunan'
            ])
            ->aktif()
            ->andWhere(['!=', 'id_pegawai', 0])
            ->andWhere(['>=', 'tanggal', '2018-01-01'])
            ->limit(100000);
        // $this->stdout($query->createCommand()->getRawSql());
        // $this->stdout($query->count());
        // die;
        $count = 100000;
        $this->stdout("$count akan dibuat instansi pegawainya.\n");
        Console::startProgress(($i = 0), $count, 'Proses :');
        foreach ($query->all() as $model) {
            $pegawai = $model->pegawai;
            $model->id_instansi_pegawai = $model->getIsKegiatanSkp() ? @$model->kegiatanTahunan->id : $pegawai->getInstansiPegawaiBerlakuEager($model->tanggal)->id;
            $model->save(false);
            Console::updateProgress(++$i, $count, 'Proses :');
        }
        Console::endProgress();
    }

    public function actionCleanStatus()
    {
        $kegHarian = KegiatanHarian::find()->andWhere(['id_kegiatan_status' => 'update_sta'])->all();
        KegiatanHarian::updateAll(['id_kegiatan_status' => 1, 'id_kegiatan_status' => 'update_sta']);
    }

    public function actionHarianByTahunan()
    {
        /*$query = KegiatanTahunan::find()->with('allKegiatanHarian');
        $count = $query->count();
        $this->stdout($count);
        Console::startProgress(($i = 0), $count, 'Proses :');
        foreach ($query->all() as $kegiatanTahunan) {
            if ($kegiatanTahunan->allKegiatanHarian !== []) {
                KegiatanHarian::updateAll(['id_instansi_pegawai' => $kegiatanTahunan->id_instansi_pegawai], ['id_kegiatan_tahunan' => $kegiatanTahunan]);
            }
            Console::updateProgress(++$i, $count, 'Proses :');
        }
        Console::endProgress();*/
        /*$query = "UPDATE kegiatan_harian
                    INNER JOIN kegiatan_tahunan
                        ON kegiatan_harian.id_kegiatan_tahunan = kegiatan_tahunan.id
                SET kegiatan_harian.id_instansi_pegawai = kegiatan_tahunan.id_instansi_pegawai";
        Yii::$app->db->createCommand($query)->execute();*/
        $query = KegiatanHarian::find()
            ->joinWith('pegawai');
        echo $query->createCommand()->getRawSql();
    }

    public function actionKegiatanHarianMutasi()
    {
        "SELECT * FROM `pegawai`
            INNER JOIN `instansi_pegawai`
            ON `instansi_pegawai`.id_pegawai = `pegawai`.`id`
            GROUP BY `pegawai`.`id`
            HAVING COUNT(`instansi_pegawai`.id) = 2
        ";
        $query = Pegawai::find()
            ->joinWith('allInstansiPegawai', true, 'INNER JOIN')
            ->groupBy('pegawai.id')
            ->having('COUNT({{%instansi_pegawai}}.id) = 2');
        $this->stdout($query->createCommand()->getRawSql());
    }
}
