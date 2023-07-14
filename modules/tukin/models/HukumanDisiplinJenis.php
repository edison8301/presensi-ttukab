<?php

namespace app\modules\tukin\models;

use Yii;

/**
 * This is the model class for table "hukuman_disiplin_jenis".
 *
 * @property int $id
 * @property string $nama
 * @property int $potongan
 * @property int $lama
 */
class HukumanDisiplinJenis extends \yii\db\ActiveRecord
{
    const RINGAN = 1;
    const SEDANG = 2;
    const BERAT = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hukuman_disiplin_jenis';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama', 'potongan', 'lama'], 'required'],
            [['potongan', 'lama'], 'integer'],
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
            'potongan' => 'Potongan',
            'lama' => 'Lama',
        ];
    }
}
