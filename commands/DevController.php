<?php

namespace app\commands;

use app\modules\absensi\models\KetidakhadiranJamKerja;
use app\modules\absensi\models\KetidakhadiranPanjang;
use Yii;
use app\components\Helper;
use app\jobs\RefreshRekapJob;
use app\models\InstansiPegawai;
use app\models\Pegawai;
use app\modules\absensi\models\JamKerja;
use app\modules\absensi\models\Ketidakhadiran;
use app\modules\absensi\models\ShiftKerja;
use app\modules\iclock\models\Iclock;
use app\modules\iclock\models\Userinfo;
use yii\console\Controller;
use yii\helpers\Console;
use yii\helpers\StringHelper;
use function count;

class DevController extends Controller
{
    public $defaultAction = 'dev';

    public function actionCek()
    {
        $pegawai = Userinfo::find()
            ->andWhere([
                'name' => ''
            ])
            ->joinWith(['templates', 'manyCheckinout'])
            ->groupBy('badgenumber')
            // ->andWhere(['template.userid' => null])
            ->count();
        echo $pegawai;
        die;
        foreach ($pegawai as $tes) {
            // $this->stdOut($tes->getTemplatesCount() . "\n");
            if ($tes->getTemplatesCount() != 0) {
                foreach ($tes->templates as $jari) {
                    $this->stdOut("$jari->FingerID \n");
                }
            }
            // $this->stdOut($tes->getCheckinoutCount() . "\n");
        }
    }

    public function actionKirimAdmin($badgenumber)
    {
        $userinfo = Userinfo::findOne(['badgenumber' => $badgenumber]);
        foreach (($iclock = Iclock::findAllMesinBabel()) as $mesin) {
            $userinfo->sendToDevice($mesin->SN);
        }
        $this->stdOut("$userinfo->name telah barhasil dikirimkan ke " . count($iclock) . ' Mesin', Console::FG_GREEN, Console::BOLD);
    }

    public function actionJob()
    {
        echo Yii::$app->queue->push(new RefreshRekapJob(['params' => serialize(['r' => 'absensi/pegawai-rekap-absensi/sakit', 'PegawaiRekapAbsensiSearch' => [ 'id_instansi' => '1', 'bulan' => '6']])]));
    }

    public function actionUpdate()
    {
        return Ketidakhadiran::updateAll(['keterangan' => '-'], 'keterangan IS NULL OR keterangan = \'\'');
    }

    public function actionSalin($fromId, $toId)
    {
        $from = ShiftKerja::findOne($fromId);
        $to = ShiftKerja::findOne($toId);
        foreach ($from->findAllJamKerja() as $jamKerja) {
            $new = new JamKerja();
            $new->attributes = $jamKerja->attributes;
            $new->id_shift_kerja = $to->id;
            $new->save(false);
        }
    }

    public function actionFlush()
    {
        Yii::$app->cache->flush();
    }

    public function actionRefreshInstansiPegawaiText()
    {
        $model = InstansiPegawai::find()
            ->all();
            $no = 0;
        foreach ($model as $data) {
            $no++;
            $data->nama_instansi = @$data->instansi->nama;

            $data->tanggal_mulai_text = Helper::getTanggal($data->tanggal_mulai);
            $data->tanggal_selesai_text = Helper::getTanggal($data->tanggal_selesai);

            if ($data->tanggal_selesai == '9999-12-31') {
                $data->tanggal_selesai_text = 'Saat Ini';
            }

            if ($data->tanggal_mulai_text == null) {
                $data->tanggal_mulai_text = '-';
            }

            if ($data->tanggal_selesai_text == null) {
                $data->tanggal_selesai_text = '-';
            }

            $data->save(false);
            echo "$data->nama_instansi - ".$no."\n";
        }
    }

    public function actionKetidakhadiran()
    {
        $data = KetidakhadiranPanjang::find()->andWhere(['id_instansi' => null])->all();
        $count = count($data);
        $i = 0;
        foreach ($data as $ketidakhadiranPanjang) {
            $pegawai = $ketidakhadiranPanjang->pegawai;
            $i++;
            $this->stdout("$i/$count ");
            if (!$pegawai) {
                $this->stdout("$ketidakhadiranPanjang->id pegawai null\n", Console::FG_RED);
                continue;
            }
            $instansiPegawai = $pegawai->getInstansiPegawaiBerlaku($ketidakhadiranPanjang->tanggal_mulai);
            if (!$instansiPegawai) {
                $this->stdout("$ketidakhadiranPanjang->id instansi pegawai null\n", Console::FG_YELLOW);
                continue;
            }
            $ketidakhadiranPanjang->updateAttributes(['id_instansi' => $instansiPegawai->id_instansi]);
            $this->stdout("$ketidakhadiranPanjang->id berhasil\n", Console::FG_GREEN);
        }
    }

    public function actionKetidakhadiranJamKerja()
    {
        $data = KetidakhadiranJamKerja::find()->andWhere(['id_instansi' => null])->all();
        $count = count($data);
        $i = 0;
        foreach ($data as $ketidakhadiranJamKerja) {
            $pegawai = $ketidakhadiranJamKerja->pegawai;
            $i++;
            $this->stdout("$i/$count ");
            if (!$pegawai) {
                $this->stdout("$ketidakhadiranJamKerja->id pegawai null\n", Console::FG_RED);
                continue;
            }
            $instansiPegawai = $pegawai->getInstansiPegawaiBerlaku($ketidakhadiranJamKerja->tanggal);
            if (!$instansiPegawai) {
                $this->stdout("$ketidakhadiranJamKerja->id instansi pegawai null\n", Console::FG_YELLOW);
                continue;
            }
            $ketidakhadiranJamKerja->updateAttributes(['id_instansi' => $instansiPegawai->id_instansi]);
            $this->stdout("$ketidakhadiranJamKerja->id berhasil\n", Console::FG_GREEN);
        }
    }
}
