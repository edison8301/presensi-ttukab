<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pegawai_rekap_jenis".
 *
 * @property int $id
 * @property string $nama
 * @property string $satuan
 * @property string $keterangan
 */
class PegawaiRekapJenis extends \yii\db\ActiveRecord
{
    const RUPIAH_TPP_AWAL = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pegawai_rekap_jenis';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama'], 'required'],
            [['keterangan'], 'string'],
            [['nama', 'satuan'], 'string', 'max' => 255],
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
            'satuan' => 'Satuan',
            'keterangan' => 'Keterangan',
        ];
    }
}
