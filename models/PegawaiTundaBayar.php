<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pegawai_tunda_bayar".
 *
 * @property int $id
 * @property int $id_pegawai
 * @property int $id_pegawai_tunda_bayar_jenis
 * @property string $tanggal_mulai
 * @property string $tanggal_selesai
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property Pegawai $pegawai
 * @property PegawaiTundaBayarJenis $pegawaiTundaBayarJenis
 */
class PegawaiTundaBayar extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pegawai_tunda_bayar';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pegawai', 'id_pegawai_tunda_bayar_jenis', 'tanggal_mulai', 'tanggal_selesai'], 'required'],
            [['id_pegawai', 'id_pegawai_tunda_bayar_jenis'], 'integer'],
            [['tanggal_mulai', 'tanggal_selesai', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
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
            'id_pegawai_tunda_bayar_jenis' => 'Pegawai Tunda Bayar Jenis',
            'tanggal_mulai' => 'Tanggal Mulai',
            'tanggal_selesai' => 'Tanggal Selesai',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * @return string
     */
    public function setTanggalSelesai(): string
    {
        if ($this->tanggal_selesai == null) {
            $this->tanggal_selesai = '9999-12-31';
        }

        return $this->tanggal_selesai;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPegawai(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Pegawai::class, ['id' => 'id_pegawai']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPegawaiTundaBayarJenis(): \yii\db\ActiveQuery
    {
        return $this->hasOne(PegawaiTundaBayarJenis::class, ['id' => 'id_pegawai_tunda_bayar_jenis']);
    }
}
