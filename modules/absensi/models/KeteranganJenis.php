<?php

namespace app\modules\absensi\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "keterangan_jenis".
 *
 * @property integer $id
 * @property string $nama
 */
class KeteranganJenis extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    CONST DINAS_LUAR = 1;
    CONST SAKIT = 2;
    CONST IZIN = 3;
    CONST CUTI = 4;
    
    public static function tableName()
    {
        return 'keterangan_jenis';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db');
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
        return ArrayHelper::map(self::find()->all(),'id','nama');
    }
}
