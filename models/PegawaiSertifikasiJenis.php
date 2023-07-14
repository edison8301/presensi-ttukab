<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "pegawai_sertifikasi_jenis".
 *
 * @property int $id
 * @property string $nama
 */
class PegawaiSertifikasiJenis extends \yii\db\ActiveRecord
{
    const GURU_SERTIFIKASI = 1;
    const DOKTER_SPESIALIS = 2;
    const DOKTER_SUBSPESIALIS = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pegawai_sertifikasi_jenis';
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

    public static function findArrayDropDownList()
    {
        return ArrayHelper::map(PegawaiSertifikasiJenis::find()->all(), 'id', 'nama');
    }
}
