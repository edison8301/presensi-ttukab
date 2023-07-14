<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "pegawai_rb_jenis".
 *
 * @property int $id
 * @property string $nama
 */
class PegawaiRbJenis extends \yii\db\ActiveRecord
{
    const PEMUTAKHIRAN_SIMADIG = 1;
    const PERENCANAAN_BANGKOM = 2;
    const REALISASI_BANGKOM = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pegawai_rb_jenis';
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

    /**
     * @return array
     */
    public static function getList(): array
    {
        return ArrayHelper::map(PegawaiRbJenis::find()->all(), 'id', 'nama');
    }
}
