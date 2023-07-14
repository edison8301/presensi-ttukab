<?php

namespace app\modules\kinerja\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "kegiatan_aspek".
 *
 * @property int $id
 * @property string $nama
 */
class KegiatanAspek extends \yii\db\ActiveRecord
{
    const KUANTITAS = 1;
    const KUALITAS = 2;
    const WAKTU = 3;
    const BIAYA = 4;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kegiatan_aspek';
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
        return ArrayHelper::map(KegiatanAspek::find()->all(), 'id', 'nama');
    }
}
