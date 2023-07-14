<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pegawai_cuti_ibadah".
 *
 * @property int $id
 * @property int $id_pegawai
 * @property string $tanggal_mulai
 * @property string $tanggal_selesai
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property Pegawai $pegawai
 */
class PegawaiCutiIbadah extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pegawai_cuti_ibadah';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pegawai', 'tanggal_mulai', 'tanggal_selesai'], 'required'],
            [['id_pegawai'], 'integer'],
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
            'tanggal_mulai' => 'Tanggal Mulai',
            'tanggal_selesai' => 'Tanggal Selesai',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPegawai(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Pegawai::class, ['id' => 'id_pegawai']);
    }
}
