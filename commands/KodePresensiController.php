<?php

namespace app\commands;

use app\models\Pegawai;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;

class KodePresensiController extends Controller
{
    public $defaultAction = 'generate-kode';

    public function actionGenerateKode()
    {
        $query = "UPDATE {{%pegawai}} SET kode_presensi = nip";
        if (($count = Yii::$app->db->createCommand($query)->execute()) !== false) {
            $this->stdout("$count Pegawai    telah diset kode_presensi-nya menjadi sama dengan NIP nya", Console::BOLD, Console::FG_GREEN);
        } else {
            $this->stdout("Gagal\n", Console::BOLD, Console::FG_RED);
            $this->stdout("Query yang dieksekusi:\n");
            $this->stdout($query);
        }
    }
}
