<?php

namespace app\modules\absensi\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "ketidakhadiran_kegiatan_jenis".
 *
 * @property int $id
 * @property string $nama
 */
class KetidakhadiranKegiatanJenis extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    const UPACARA = 1;
    const SENAM = 2;
    const APEL_PAGI = 3;
    const APEL_SORE = 4;

    public static function tableName()
    {
        return 'ketidakhadiran_kegiatan_jenis';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama'], 'required'],
            [['nama'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
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
        return ArrayHelper::map(KetidakhadiranKegiatanJenis::find()->all(),'id','nama');
    }
}
