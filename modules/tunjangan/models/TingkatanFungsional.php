<?php

namespace app\modules\tunjangan\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tingkatan_fungsional".
 *
 * @property int $id
 * @property string $nama
 */
class TingkatanFungsional extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tingkatan_fungsional';
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
