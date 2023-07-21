<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kegiatan_ketidakhadiran_jenis".
 *
 * @property int $id
 * @property string $nama
 */
class KegiatanKetidakhadiranJenis extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kegiatan_ketidakhadiran_jenis';
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
