<?php

namespace app\modules\absensi\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "ketidakhadiran_kegiatan_status".
 *
 * @property int $id
 * @property string $nama
 */
class KetidakhadiranKegiatanStatus extends \yii\db\ActiveRecord
{
    const SETUJU = 1;
    const PROSES = 2;
    const TOLAK = 3;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ketidakhadiran_kegiatan_status';
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
        return ArrayHelper::map(KetidakhadiranKegiatanStatus::find()->all(),'id','nama');
    }
}
