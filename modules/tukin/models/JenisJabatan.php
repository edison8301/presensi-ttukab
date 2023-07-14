<?php

namespace app\modules\tukin\models;

use Yii;

/**
 * This is the model class for table "jenis_jabatan".
 *
 * @property int $id
 * @property int $id_kelompok_jabatan
 * @property int $id_induk
 * @property string $nama
 */

class JenisJabatan extends \yii\db\ActiveRecord
{
    const PELAKSANA = 3;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jenis_jabatan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_kelompok_jabatan', 'nama'], 'required'],
            [['id_kelompok_jabatan', 'id_induk'], 'integer'],
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
            'id_kelompok_jabatan' => 'Id Kelompok Jabatan',
            'id_induk' => 'Id Induk',
            'nama' => 'Nama',
        ];
    }
}
