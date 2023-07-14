<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pegawai_tunjangan_rincian".
 *
 * @property int $id
 * @property int $id_pegawai_rincian
 * @property int $id_pegawai
 * @property int $tahun
 * @property int $bulan
 * @property int $kode
 * @property string $nilai
 */
class PegawaiTunjanganRincian extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pegawai_tunjangan_rincian';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pegawai_rincian', 'id_pegawai', 'tahun', 'bulan', 'kode', 'nilai'], 'required'],
            [['id_pegawai_rincian', 'id_pegawai', 'tahun', 'bulan', 'kode'], 'integer'],
            [['nilai'], 'string', 'max' => 20],
            [['id_pegawai', 'tahun', 'bulan', 'kode'], 'unique', 'targetAttribute' => ['id_pegawai', 'tahun', 'bulan', 'kode']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_pegawai_rincian' => 'Id Pegawai Rincian',
            'id_pegawai' => 'Id Pegawai',
            'tahun' => 'Tahun',
            'bulan' => 'Bulan',
            'kode' => 'Kode',
            'nilai' => 'Nilai',
        ];
    }

    /**
     * {@inheritdoc}
     * @return PegawaiTunjanganRincianQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PegawaiTunjanganRincianQuery(get_called_class());
    }
}
