<?php

namespace app\modules\absensi\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "ketidakhadiran_kegiatan_keterangan".
 *
 * @property int $id
 * @property string $nama
 */
class KetidakhadiranKegiatanKeterangan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ketidakhadiran_kegiatan_keterangan';
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
        return ArrayHelper::map(KetidakhadiranKegiatanKeterangan::find()->all(),'id','nama');
    }
}
