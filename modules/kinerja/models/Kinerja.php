<?php

namespace app\modules\kinerja\models;

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
class Kinerja extends \yii\base\Model
{

    const KEGIATAN_SKP = 1;
    const KEGIATAN_TAMBAHAN = 2;


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode_unit_kerja' => 'Kode Perangkat Daerah',
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
        if(User::isAdmin()) {
            return true;
        }

        if(User::isVerifikator() or User::isInstansi()) {
            return true;
        }

        return false;
    }
}
