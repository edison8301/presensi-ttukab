<?php

namespace app\jobs;

use Yii;
use app\models\Instansi;
use app\modules\absensi\models\PegawaiRekapAbsensiSearch;
use yii\helpers\Console;


class RefreshRekapJob extends BaseJob implements \yii\queue\JobInterface
{
    public $params;

    public $id_instansi;

    public function init()
    {
        $this->params = unserialize($this->params);
    }

    public function execute($queue)
    {
        try {
            $this->proses();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    private function proses()
    {
        $searchModel = new PegawaiRekapAbsensiSearch();
        $allData = $searchModel->getQuerySearch($this->params)->with('pegawai')->all();
        $count = count($allData);
        $prog = 0;
        Console::startProgress(0, $count, 'Merefresh Rekap: ', false);
        foreach ($allData as $data) {
            @$data->pegawai->getPotonganBulan($searchModel->bulan);
            @$data->pegawai->updatePegawaiRekapAbsensi($searchModel->bulan);
            Console::updateProgress(++$prog, $count);
        }
        Console::endProgress("$prog/$count Done :)");
        echo PHP_EOL;
        // Start to write log
        $instansi = Instansi::findOne($this->id_instansi);
        $dirname = str_replace('/', '-', $instansi->nama);
        file_put_contents("runtime/log_instansi_$dirname ($instansi->id) " . date('Y-m-d') . ".txt", "$prog/$count\n$instansi->nama\n$instansi->id");
    }
}
