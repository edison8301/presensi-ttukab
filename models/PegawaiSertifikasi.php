<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pegawai_sertifikasi".
 *
 * @property int $id
 * @property int $id_pegawai
 * @property int $id_instansi
 * @property string $tanggal_mulai
 * @property string $tanggal_selesai
 * @property int $id_pegawai_sertifikasi_jenis
 * @property Pegawai $pegawai
 * @property Instansi $instansi
 */
class PegawaiSertifikasi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pegawai_sertifikasi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pegawai'], 'required'],
            [['id_pegawai'], 'integer'],
            [['tanggal_mulai', 'tanggal_selesai'], 'safe'],
            [['id_pegawai_sertifikasi_jenis'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_pegawai' => 'Pegawai',
            'id_instansi' => 'Unit Kerja',
            'tanggal_mulai' => 'Tanggal Mulai',
            'tanggal_selesai' => 'Tanggal Selesai',
            'id_pegawai_sertifikasi_jenis' => 'Jenis',
        ];
    }

    public function getPegawai()
    {
        return $this->hasOne(Pegawai::class,['id'=>'id_pegawai']);
    }

    public function getInstansi()
    {
        return $this->hasOne(Instansi::class,['id'=>'id_instansi']);
    }

    public function getPegawaiSertifikasiJenis()
    {
        return $this->hasOne(PegawaiSertifikasiJenis::class,['id'=>'id_pegawai_sertifikasi_jenis']);
    }

    public function setIdInstansi()
    {
        if($this->pegawai === null) {
            return false;
        }

        $instansiPegawai = $this->pegawai->getInstansiPegawaiBerlaku();

        if($instansiPegawai === null) {
            return false;
        }

        $this->id_instansi = $instansiPegawai->id_instansi;

        return $this->id_instansi;

    }
}
