<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kegiatan_realisasi_status".
 *
 * @property int $id
 * @property string $nama
 */
class KegiatanRealisasiStatus extends \yii\db\ActiveRecord
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
        return 'kegiatan_realisasi_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama'], 'required'],
            [['nama'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama' => 'Nama',
        ];
    }
}
