<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "pegawai_jenis".
 *
 * @property int $id
 * @property string $nama
 */
class PegawaiJenis extends \yii\db\ActiveRecord
{
    const PNS = 1;
    const P3K = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pegawai_jenis';
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
        $query = self::find();
        return ArrayHelper::map($query->all(), 'id', 'nama');
    }
}
