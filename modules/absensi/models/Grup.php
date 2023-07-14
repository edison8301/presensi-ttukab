<?php

namespace app\modules\absensi\models;

use Yii;

/**
 * This is the model class for table "grup".
 *
 * @property int $id
 * @property string $nama
 * @property int $id_instansi
 */
class Grup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'grup';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama'], 'required'],
            [['id_instansi'], 'integer'],
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
            'id_instansi' => 'Id Instansi',
        ];
    }
}
