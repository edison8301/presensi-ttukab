<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pegawai_atribut".
 *
 * @property int $id
 * @property int $id_pegawai
 * @property string $keterangan
 * @property string $tanggal
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property Pegawai $pegawai

 */
class PegawaiAtribut extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pegawai_atribut';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pegawai', 'keterangan', 'tanggal'], 'required'],
            [['id_pegawai'], 'integer'],
            [['keterangan'], 'string'],
            [['tanggal', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
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
            'keterangan' => 'Keterangan',
            'tanggal' => 'Tanggal',
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
