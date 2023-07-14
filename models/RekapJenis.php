<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rekap_jenis".
 *
 * @property int $id
 * @property string $nama
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class RekapJenis extends \yii\db\ActiveRecord
{
    const JUMLAH_PEGAWAI_POTONGAN_CKHP = 1;
    const JUMLAH_PEGAWAI_POTONGAN_IKI = 2;
    const JUMLAH_TPP_KOTOR = 3;
    const INDEKS_IP_ASN = 4;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rekap_jenis';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama'], 'required'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
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
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }
}
