<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pegawai_non_tpp".
 *
 * @property int $id
 * @property int $id_pegawai
 * @property string $tanggal_mulai
 * @property string $tanggal_selesai
 */
class PegawaiNonTpp extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pegawai_non_tpp';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pegawai', 'tanggal_mulai', 'tanggal_selesai'], 'required'],
            [['id_pegawai', 'id_pegawai_non_tpp_jenis'], 'integer'],
            [['tanggal_mulai', 'tanggal_selesai'], 'safe'],
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
            'tanggal_mulai' => 'Tanggal Mulai',
            'tanggal_selesai' => 'Tanggal Selesai',
        ];
    }

    public function getPegawai()
    {
        return $this->hasOne(Pegawai::class, ['id' => 'id_pegawai']);
    }

    public function setTanggalSelesai()
    {
        if ($this->tanggal_selesai == null) {
            $this->tanggal_selesai = '9999-12-31';
        }

        return $this->tanggal_selesai;
    }
}
