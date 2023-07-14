<?php

namespace app\commands;

use app\modules\absensi\models\UploadPresensi;
use app\modules\absensi\models\UploadPresensiLog;
use app\modules\iclock\models\Checkinout;
use app\modules\iclock\models\Userinfo;
use kartik\base\Config;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use yii\httpclient\Client;

class CheckinoutController extends Controller
{
    public $id_checkinout;
    public $status_kirim;
    public $tanggal_awal;
    public $tanggal_akhir;
    public $limit;
    public $tes;

    public function options($actionID)
    {
        return [
            'id_checkinout',
            'status_kirim',
            'tanggal_awal',
            'tanggal_akhir',
            'limit',
            'tes'
        ];
    }

    public function actionIndex()
    {
        $this->stdout("Hello World\n");
        $this->stdout($this->tes);
    }

    public function actionImport($SN,$file)
    {
        $direktori = Yii::getAlias('@app') . "/web/uploads/".$file;
        //$SN = $this->SN === null ? explode("_", $this->file)[0] : $this->SN;
        echo "SN: $SN \n";
        // $this->stdout($direktori);
        if (is_file($direktori)) {
            // echo "File $direktori Ditemukan \n";
            $file = file_get_contents($direktori);
            $users = explode("\n", $file);
            $jumlah = count($users);
            $this->stdout("$jumlah Data ... ...\n");
            Console::startProgress(0, $jumlah, 'Counting objects: ', false);
            $n = 0;
            $debug = null;
            foreach ($users as $user) {
                $data = explode("\t", $user);
                if (count($data) < 6) {
                    continue;
                }
                $log = new UploadPresensiLog([
                    'id_upload_presensi' => 0,
                    'badgenumber' => $data[0],
                    'checktime' => $data[1],
                    'checktype' => $data[3],
                    'SN' => $SN
                ]);
                $data[0] = str_replace(' ', '', $data[0]);
                $userinfo = Userinfo::findOne(['badgenumber' => $data[0]]);
                $n++;
                if ($userinfo === null) {
                    // echo "User dengan pin $data[0] tidak ditemukan\n";
                    Console::clearLine();
                    $this->stdout("\rUser dengan pin $data[0] tidak ditemukan\n", Console::BOLD, Console::FG_RED);
                    $log->status_kirim = UploadPresensiLog::STATUS_NO_USER;
                    Console::updateProgress($n, $jumlah);
                    continue;
                }
                $new = new Checkinout([
                    'userid' => $userinfo->userid,
                    'checktime' => $data[1],
                    'checktype' => $data[3],
                    'verifycode' => 1,
                    'SN' => $SN,
                    'sensorid' => "11905",
                    'WorkCode' => "0",
                    'Reserved' => $SN,
                ]);
                if (!$new->save()) {
                    // echo "data ke $n Sudah diinputkan\n";
                    Console::clearLine();
                    $errorSummary = $new->getErrorSummary(false);
                    $log->debug = implode(',', $errorSummary);
                    if (array_search("The combination of Userid and Checktime has already been taken.", $errorSummary) !== false) {
                        $this->stdout("\rdata ke $n Sudah diinputkan\n", Console::FG_YELLOW);
                        $log->status_kirim = UploadPresensiLog::STATUS_SUDAH_TERKIRIM;
                    } else {
                        $this->stdout("\rdata ke $n Gagal diinputkan\n", Console::FG_RED);
                        $log->status_kirim = UploadPresensiLog::STATUS_GAGAL_KIRIM;
                    }
                } else {
                    // echo "data ke $n Berhasil diinput\n";
                    Console::clearLine();
                    $log->status_kirim = UploadPresensiLog::STATUS_TERKIRIM;
                    $this->stdout("\rdata ke $n Sukses\n", Console::FG_GREEN);
                }
                $log->save();
                Console::updateProgress($n, $jumlah);
            }
            Console::endProgress("$n/$jumlah Done :)" . PHP_EOL);
            //UploadPresensi::updateAll(['status' => 1], ['id' => $this->id]);
            return true;
        } else {
            //UploadPresensi::updateAll(['status' => 2], ['id' => $this->id]);
            return true;
        }
    }

    public function actionSendToDebian()
    {
        if($this->tanggal_awal == null AND $this->id_checkinout == null) {
            $this->stdout("Parameter tanggal_awal atau id tidak boleh kosong dua-duanya\n");
            return;
        }

        if($this->id_checkinout == null AND $this->status_kirim == null) {
            $this->status_kirim = 0;
        }

        $query = Checkinout::find();

        if($this->id_checkinout != null) {
            $query->andWhere(['id'=>$this->id_checkinout]);
        }

        if($this->status_kirim != null) {
            $query->andWhere('status_kirim = :status_kirim',[
                ':status_kirim' => $this->status_kirim
            ]);
        }

        if($this->tanggal_awal != null) {
            $query->andWhere('checktime >= :checktime_awal',[
                ':checktime_awal' => $this->tanggal_awal.' 00:00:00'
            ]);
        }

        if($this->tanggal_akhir != null) {
            $query->andWhere('checktime <= :checktime_akhir',[
                ':checktime_akhir' => $this->tanggal_akhir.' 23:59:59'
            ]);
        }

        if($this->limit != null) {
            $query->limit($this->limit);
        }

        $total = $query->count();
        $done = 0;

        $this->stdout("Jumlah Data : $total \n");

        $i=1;
        foreach($query->all() as $data) {
            $client = new Client();
            $request = $client->createRequest()
                ->setMethod('GET')
                ->setUrl(\app\components\Config::getUrlDebian())
                ->setData([
                    'r'=>'/api/checkinout/create',
                    'token'=>'DGEsdge425234SRfewrASDAFw3rSDFwer',
                    'userid'=>$data->userid,
                    'checktime'=>$data->checktime,
                    'checktype'=>$data->checktype,
                    'verifycode'=>$data->verifycode,
                    'SN'=>$data->SN,
                    'sensorid'=>$data->sensorid,
                    'WorkCode'=>$data->WorkCode,
                    'Reserved'=>$data->Reserved
                ]);

            $response = $request->send();

            $this->stdout("$i - ");
            $this->stdout('ID: '.$data->id.', Status Code : '.$response->getStatusCode());

            $i++;

            if($response->isOk) {

                //this->stdout(", Response : ".$response->data."\n");

                if($response->data == "true") {
                    $data->updateAttributes([
                        'status_kirim' => Checkinout::STATUS_KIRIM_SUKSES,
                        'waktu_kirim'=>date('Y-m-d H:i:s')
                    ]);

                } else {
                    $data->updateAttributes([
                        'status_kirim' => Checkinout::STATUS_KIRIM_GAGAL_SIMPAN,
                        'waktu_kirim'=>date('Y-m-d H:i:s')
                    ]);

                    $this->stdout("Data gagal disimpan di server\n");
                    $this->stdout($request->toString()."\n");

                }

            } else {
                $data->updateAttributes([
                    'status_kirim' => Checkinout::STATUS_KIRIM_GAGAL_KONEKSI,
                    'waktu_kirim'=>date('Y-m-d H:i:s')
                ]);

                $this->stdout($request->toString()."\n");

            }
        }
    }
}
