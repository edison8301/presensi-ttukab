<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "pegawai_penghargaan_tingkat".
 *
 * @property int $id
 * @property string $nama
 */
class PegawaiPenghargaanTingkat extends \yii\db\ActiveRecord
{
    const PROVINSI = 1;
    const NASIONAL = 2;
    const INTERNASIONAL = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pegawai_penghargaan_tingkat';
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
        return ArrayHelper::map(self::find()->all(), 'id', 'nama');
    }
}
