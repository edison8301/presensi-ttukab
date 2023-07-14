<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ref_kegiatan_realisasi_status".
 *
 * @property int $id
 * @property string $kode_kegiatan_realisasi_status
 * @property string $nama
 *
 * @property KegiatanRealisasi[] $kegiatanRealisasis
 */
class RefKegiatanRealisasiStatus extends \yii\db\ActiveRecord
{
    const DISETUJUI = 1;
    const KONSEP = 2;
    const DIVERIFIKASI = 3;
    const DITOLAK = 4;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ref_kegiatan_realisasi_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kode_kegiatan_realisasi_status', 'nama'], 'required'],
            [['kode_kegiatan_realisasi_status'], 'string', 'max' => 50],
            [['nama'], 'string', 'max' => 255],
            [['kode_kegiatan_realisasi_status'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode_kegiatan_realisasi_status' => 'Kode Kegiatan Realisasi Status',
            'nama' => 'Nama',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKegiatanRealisasis()
    {
        return $this->hasMany(KegiatanRealisasi::className(), ['kode_kegiatan_realisasi_status' => 'kode_kegiatan_realisasi_status']);
    }
}
