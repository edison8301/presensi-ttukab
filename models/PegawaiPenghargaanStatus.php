<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "pegawai_penghargaan_status".
 *
 * @property int $id
 * @property string $nama
 */
class PegawaiPenghargaanStatus extends \yii\db\ActiveRecord
{
    const SETUJU = 1;
    const PROSES = 2;
    const TOLAK = 3;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pegawai_penghargaan_status';
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
