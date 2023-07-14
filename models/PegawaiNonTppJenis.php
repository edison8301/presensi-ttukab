<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pegawai_non_tpp_jenis".
 *
 * @property int $id
 * @property string $nama
 */
class PegawaiNonTppJenis extends \yii\db\ActiveRecord
{

    const CUTI_BESAR = 1;
    const TUGAS_BELAJAR = 2;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pegawai_non_tpp_jenis';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama'], 'required'],
            [['nama'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama' => 'Nama',
        ];
    }
}
