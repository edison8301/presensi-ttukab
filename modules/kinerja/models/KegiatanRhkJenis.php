<?php

namespace app\modules\kinerja\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "kegiatan_rhk_jenis".
 *
 * @property int $id
 * @property string $nama
 */
class KegiatanRhkJenis extends \yii\db\ActiveRecord
{
    const UTAMA = 1;
    const TAMBAHAN = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kegiatan_rhk_jenis';
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

    public static function getList()
    {
        return ArrayHelper::map(KegiatanRhkJenis::find()->all(), 'id', 'nama');
    }
}
