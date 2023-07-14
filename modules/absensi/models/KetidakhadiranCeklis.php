<?php

namespace app\modules\absensi\models;

use app\models\Pegawai;

/**
 * This is the model class for table "ketidakhadiran_ceklis".
 *
 * @property int $id
 * @property int $id_pegawai
 * @property string $tanggal
 * @property int $id_jam_kerja
 * @property string $keterangan
 *
 * @property Pegawai $pegawai
 * @property JamKerja $jamKerja
 */
class KetidakhadiranCeklis extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ketidakhadiran_ceklis';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pegawai', 'tanggal', 'id_jam_kerja'], 'required'],
            [['id_pegawai', 'id_jam_kerja'], 'integer'],
            [['tanggal'], 'safe'],
            [['keterangan'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_pegawai' => 'Id Pegawai',
            'tanggal' => 'Tanggal',
            'id_jam_kerja' => 'Id Jam Kerja',
            'keterangan' => 'Keterangan',
        ];
    }

    public function getPegawai()
    {
        return $this->hasOne(Pegawai::class, ['id' => 'id_pegawai']);
    }

    public function getJamKerja()
    {
        return $this->hasOne(JamKerja::class, ['id' => 'id_jam_kerja']);
    }
}
