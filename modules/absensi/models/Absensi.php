<?php

namespace app\modules\absensi\models;

use app\components\Session;
use DateTime;
use Yii;
use app\models\User;

/**
 * This is the model class for table "absensi".
 *
 * @property integer $id
 * @property integer $kode_unit_kerja
 * @property integer $kode_pegawai
 * @property string $waktu_absensi
 * @property string $waktu_dibuat
 */
class Absensi extends \yii\base\Model
{

    const POTONGAN_TANPA_KETERANGAN = 16;
    const KETIDAKHADIRAN_IZIN = 1;
    const KETIDAKHADIRAN_SAKIT = 2;
    const KETIDAKHADIRAN_CUTI = 3;
    const KETIDAKHADIRAN_DINAS_LUAR = 4;
    const KETIDAKHADIRAN_TUGAS_BELAJAR = 5;
    const KETIDAKHADIRAN_TUGAS_KEDINASAN = 6;
    const KETIDAKHADIRAN_ALASAN_TEKNIS = 7;
    const KETIDAKHADIRAN_SETUJU = 1;
    const KETIDAKHADIRAN_PROSES = 2;
    const KETIDAKHADIRAN_TOLAK = 3;

    const KETIDAKHADIRAN_KEGIATAN_TANPA_KETERANGAN = 1;
    const KETIDAKHADIRAN_KEGIATAN_UPACARA = 1;
    const KETIDAKHADIRAN_KEGIATAN_SENAM = 2;
    const KETIDAKHADIRAN_KEGIATAN_APEL_PAGI = 3;
    const KETIDAKHADIRAN_KEGIATAN_APEL_SORE = 4;
    const KETIDAKHADIRAN_KEGIATAN_SIDAK = 5;

    const INTERVAL_1_SD_15 = 1;
    const INTERVAL_16_SD_30 = 2;
    const INTERVAL_31_KE_ATAS = 3;
    const INTERVAL_TIDAK_PRESENSI = 4;

    public static function getPotonganTanpaKeterangan(DateTime $dateTime)
    {
        if ($dateTime->format('m') < 10
            and (int) $dateTime->format('Y') === 2018) {
            return 16;
        } else {
            return 8;
        }
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode_unit_kerja' => 'Kode Unit Kerja',
            'kode_pegawai' => 'Kode Pegawai',
            'waktu_absensi' => 'Waktu Absensi',
            'waktu_dibuat' => 'Waktu Dibuat',
        ];
    }

    public function getJumlahHariKerja($params=[])
    {
        $tahun = date('Y');
        $bulan = date('n');

        if(!empty($params['tahun']))
            $tahun = $params['tahun'];

        if(!empty($params['bulan']))
            $bulan = $params['bulan'];

        $dateTime = date_create($tahun.'-'.$bulan.'-01');

        $jumlahHari = $dateTime->format('t');

        $jumlahHariKerja = 0;
        for($i=1;$i<=$jumlahHari;$i++)
        {
            if($dateTime->format('N') != 6 AND
                $dateTime->format('N') != 7 AND
                \app\modules\absensi\models\HariLibur::isLibur($dateTime->format('Y-m-d')) == false
            )
                $jumlahHariKerja++;

            $dateTime->modify('+1 day');
        }

        return $jumlahHariKerja;

    }

    public static function isHariLibur($tanggal)
    {
        $query = HariLibur::find();
        $query->andWhere(['tanggal'=>$tanggal]);

        if($query->count()!=0) {
            return true;
        }

        return false;

    }

    public static function accessPegawaiView()
    {
        return User::isAdmin() or User::isVerifikator()
            or User::isInstansi() or User::isPegawai()
            or User::isAdminInstansi() or User::isOperatorAbsen()
            or Session::isPemeriksaAbsensi();
    }
}
