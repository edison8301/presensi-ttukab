<?php
/**
 * Created by PhpStorm.
 * User: iqbal
 * Date: 15/03/2018
 * Time: 3:02 PM
 */

namespace app\commands;

use app\models\InstansiPegawai;
use app\models\Pegawai;
use app\modules\kinerja\models\KegiatanTahunan;
use yii\console\Controller;
use yii\helpers\Console;

class SkpController extends Controller
{
    public function actionGenerateInstansiPegawai($replace = false)
    {
        $allPegawai = Pegawai::find()->with('allInstansiPegawai')->all();
        $count = count($allPegawai);
        $i = 0;
        Console::startProgress($i, $count, 'Proses :');
        foreach ($allPegawai as $pegawai) {
            if ($replace === true OR (int)count($pegawai->allInstansiPegawai) === 0) {
                $instansiPegawai = new InstansiPegawai([
                    'id_pegawai' => $pegawai->id,
                    'id_instansi' => $pegawai->id_instansi,
                    'id_atasan' => $pegawai->id_atasan,
                    'id_golongan' => $pegawai->id_golongan,
                    'id_eselon' => $pegawai->id_eselon,
                    'nama_jabatan' => $pegawai->nama_jabatan,
                    'tanggal_berlaku' => date('Y-01-01')
                ]);
                $instansiPegawai->save(false);
                KegiatanTahunan::updateAll(['id_instansi_pegawai' => $instansiPegawai->id], ['id_pegawai' => $pegawai->id]);
            }
            Console::updateProgress(++$i, $count, 'Proses :');
        }
        Console::endProgress();
    }
}
