<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "instansi_jenis".
 *
 * @property int $id
 * @property string $nama
 */
class InstansiJenis extends \yii\db\ActiveRecord
{
    const UTAMA = 1;
    const UPTD = 2;
    const SEKOLAH = 3;
    const CABANG_DINAS = 4;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'instansi_jenis';
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
        return ArrayHelper::map(InstansiJenis::find()->all(),'id','nama');
    }
}
