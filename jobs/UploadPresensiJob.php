<?php
/**
 * Created by PhpStorm.
 * User: iqbal
 * Date: 3/6/2018
 * Time: 9:07 AM
 */

namespace app\jobs;

use app\models\Pegawai;
use app\modules\absensi\models\MesinAbsensi;
use Yii;
use app\modules\absensi\models\UploadPresensi;
use app\modules\absensi\models\UploadPresensiLog;
use app\modules\iclock\models\Checkinout;
use app\modules\iclock\models\Userinfo;
use yii\helpers\Console;
use yii\queue\JobInterface;

class UploadPresensiJob extends BaseJob implements JobInterface
{
    public $id;
    public $file;
    public $SN;

    public function execute($queue)
    {
        try {
            $this->proses();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function proses()
    {
        $direktori = Yii::getAlias('@app') . "/web/uploads/$this->file";
        $SN = $this->SN === null ? explode("_", $this->file)[0] : $this->SN;
        echo "SN: $SN \n";

        /* @var $mesinAbsensi MesinAbsensi */
        $mesinAbsensi  = MesinAbsensi::find()
            ->andWhere(['serialnumber' => $SN])
            ->one();

        $datetime = \DateTime::createFromFormat('Y-n-d',date('Y').'-'.date('n').'-01');

        $query = Pegawai::find();
        $query->joinWith(['manyInstansiPegawai']);
        $query->andWhere(['instansi_pegawai.id_instansi' => @$mesinAbsensi->id_instansi]);
        $query->andWhere('instansi_pegawai.tanggal_mulai <= :tanggal AND instansi_pegawai.tanggal_selesai >= :tanggal',[
            ':tanggal' => $datetime->format('Y-m-15')
        ]);

        $query->select('nip');
        $arrayNip = $query->column();

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
                    'id_upload_presensi' => $this->id,
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
                $checktype = '1';
                $new = new Checkinout([
                    'userid' => $userinfo->userid,
                    'checktime' => $data[1],
                    'checktype' => $checktype,
                    'verifycode' => 1,
                    'SN' => $SN,
                    'sensorid' => "11905",
                    'WorkCode' => "0",
                    'Reserved' => $SN,
                ]);

                if (in_array($data[0], $arrayNip) == false) {
                    Console::clearLine();
                    $this->stdout("\rPegawai dengan nip $data[0] tidak sesuai dengan SN $SN \n", Console::BOLD, Console::FG_RED);
                    $log->status_kirim = UploadPresensiLog::STATUS_GAGAL_KIRIM;
                    Console::updateProgress($n, $jumlah);
                    continue;
                }

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
                        $this->stdout(print_r($errorSummary).' : checktype '.$data[3].PHP_EOL);
                        $log->status_kirim = UploadPresensiLog::STATUS_GAGAL_KIRIM;
                    }
                } else {
                    // echo "data ke $n Berhasil diinput\n";
                    Console::clearLine();
                    $log->status_kirim = UploadPresensiLog::STATUS_TERKIRIM;
                    $this->stdout("\rdata ke $n Sukses".PHP_EOL, Console::FG_GREEN);
                }
                $log->save();
                Console::updateProgress($n, $jumlah);
            }
            Console::endProgress("$n/$jumlah Done :)" . PHP_EOL);
            UploadPresensi::updateAll(['status' => 1], ['id' => $this->id]);
            return true;
        } else {
            UploadPresensi::updateAll(['status' => 2], ['id' => $this->id]);
            return true;
        }
    }
}
