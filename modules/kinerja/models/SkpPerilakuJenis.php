<?php

namespace app\modules\kinerja\models;

use Yii;

/**
 * This is the model class for table "skp_perilaku_jenis".
 *
 * @property int $id
 * @property string $nama
 * @property string $uraian
 */
class SkpPerilakuJenis extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'skp_perilaku_jenis';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama'], 'required'],
            [['uraian'], 'string'],
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
            'uraian' => 'Uraian',
        ];
    }
}
