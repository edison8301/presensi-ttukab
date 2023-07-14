<?php

namespace app\modules\absensi\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "pegawai_dispensasi_jenis".
 *
 * @property int $id
 * @property string $nama
 */
class PegawaiDispensasiJenis extends \yii\db\ActiveRecord
{
    const FULL = 1;
    const ABSENSI = 2;
    const CKHP = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pegawai_dispensasi_jenis';
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

    public static function list()
    {
        $query = PegawaiDispensasiJenis::find();
        return ArrayHelper::map($query->all(),'id','nama');
    }
}
