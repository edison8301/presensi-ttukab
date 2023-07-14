<?php

namespace app\commands;

use app\models\Pegawai;
use Yii;
use app\jobs\RefreshRekapJob;
use app\models\Instansi;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;

class RekapController extends Controller
{
    public function actionIndex($bulan)
    {
        foreach (Instansi::find()->all() as $instansi) {
            echo Yii::$app->queue->push(
                new RefreshRekapJob([
                    'params' => serialize([
                            'r' => 'absensi/pegawai-rekap-absensi/sakit',
                            'PegawaiRekapAbsensiSearch' => [
                                'id_instansi' => $instansi->id, 'bulan' => $bulan
                            ]
                        ]),
                    'id_instansi' => $instansi->id
                ])
            );
            echo PHP_EOL;
        }
    }

    public function actionKinerjaBulan($bulan)
    {
        $query = Instansi::find()->andWhere(['id_instansi_jenis' => 1])->all();
        $count = count($query);
        $done = 0;
        foreach ($query as $instansi) {
            $done++;
            $this->stdout("$done / $count - Instansi : $instansi->nama " . PHP_EOL, Console::FG_GREEN, Console::BOLD);
            $this->actionKinerja($instansi->id, $bulan);
        }
        $this->stdout("$done / $count" . PHP_EOL);
        return ExitCode::OK;
    }

    public function actionKinerja($id_instansi, $bulan)
    {
        $query = Pegawai::find()
            ->andWhere(['id_instansi' => $id_instansi])
            ->all();
        $instansi = Instansi::findOne($id_instansi);
        $count = count($query);
        Console::startProgress(0, $count, "Generating Pegawai Rekap Kinerja - : $instansi->nama :: ", false);
        $i = 1;
        foreach ($query as $pegawai) {
            Console::updateProgress($i++, $count);
            $pegawai->updatePegawaiRekapKinerja($bulan);
        }
        Console::endProgress();
        $this->stdout(PHP_EOL);
        return ExitCode::OK;
    }
}
