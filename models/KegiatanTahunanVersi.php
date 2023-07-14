<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kegiatan_tahunan_versi".
 *
 * @property int $id
 * @property string $nama
 */
class KegiatanTahunanVersi extends \yii\db\ActiveRecord
{

    const PP_46_TAHUN_2011 = 1;
    const PP_30_TAHUN_2O19 = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kegiatan_tahunan_versi';
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
