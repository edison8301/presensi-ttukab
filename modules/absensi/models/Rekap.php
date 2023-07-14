<?php

namespace app\modules\absensi\models;

use Yii;

/**
 * This is the model class for table "rekap".
 *
 * @property integer $id
 * @property string $nip
 * @property integer $bulan
 * @property string $tahun
 * @property integer $jumlah_absensi
 * @property integer $jumlah_menit_telat
 * @property integer $jumlah_hadir
 * @property integer $jumlah_tidak_hadir
 * @property integer $jumlah_hari
 * @property integer $jumlah_dinas_luar
 * @property integer $jumlah_sakit
 * @property integer $jumlah_izin
 * @property integer $jumlah_cuti
 * @property integer $jumlah_tanpa_keterangan
 * @property integer $jumlah_persen_potongan
 */
class Rekap extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rekap';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nip', 'bulan', 'tahun'], 'required'],
            [['bulan', 'jumlah_absensi', 'jumlah_menit_telat', 'jumlah_hadir', 'jumlah_tidak_hadir', 'jumlah_hari', 'jumlah_dinas_luar', 'jumlah_sakit', 'jumlah_izin', 'jumlah_cuti', 'jumlah_tanpa_keterangan', 'jumlah_persen_potongan'], 'integer'],
            [['tahun'], 'safe'],
            [['nip'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nip' => 'Nip',
            'bulan' => 'Bulan',
            'tahun' => 'Tahun',
            'jumlah_absensi' => 'Jumlah Absensi',
            'jumlah_menit_telat' => 'Jumlah Menit Telat',
            'jumlah_hadir' => 'Jumlah Hadir',
            'jumlah_tidak_hadir' => 'Jumlah Tidak Hadir',
            'jumlah_hari' => 'Jumlah Hari',
            'jumlah_dinas_luar' => 'Jumlah Dinas Luar',
            'jumlah_sakit' => 'Jumlah Sakit',
            'jumlah_izin' => 'Jumlah Izin',
            'jumlah_cuti' => 'Jumlah Cuti',
            'jumlah_tanpa_keterangan' => 'Jumlah Tanpa Keterangan',
            'jumlah_persen_potongan' => 'Jumlah Persen Potongan',
        ];
    }
}
