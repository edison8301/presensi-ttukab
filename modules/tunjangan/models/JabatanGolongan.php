<?php

namespace app\modules\tunjangan\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "jabatan_golongan".
 *
 * @property int $id
 * @property string $nama
 */
class JabatanGolongan extends \yii\db\ActiveRecord
{

    const GOLONGAN_I = 4;
    const GOLONGAN_II = 3;
    const GOLONGAN_III = 2;
    const GOLONGAN_IV = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jabatan_golongan';
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
        return ArrayHelper::map(self::find()->all(), 'id', 'nama');
    }
}
