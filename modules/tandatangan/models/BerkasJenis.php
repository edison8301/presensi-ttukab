<?php

namespace app\modules\tandatangan\models;

use Yii;

/**
 * This is the model class for table "berkas_jenis".
 *
 * @property int $id
 * @property string $nama
 */
class BerkasJenis extends \yii\db\ActiveRecord
{
    const PERHITUNGAN_TPP = 1;
    const PEMBAYARAN_TPP = 2;
    const LEMBAR_3 = 3;
    const REKAP_ABSENSI = 4;
    const REKAP_SKP_DAN_RKB = 5;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'berkas_jenis';
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
}
