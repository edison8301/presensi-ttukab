<?php

namespace app\modules\kinerja\models;

use Yii;

/**
 * This is the model class for table "riwayat_jenis".
 *
 * @property int $id
 * @property string $nama
 */
class RiwayatJenis extends \yii\db\ActiveRecord
{
    const TAMBAH = 1;
    const SUNTING = 2;
    const HAPUS = 3;
    const DELETE = 3;
    const SETUJU = 4;
    const KONSEP = 5;
    const PERIKSA = 6;
    const TOLAK = 7;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'riwayat_jenis';
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

    public static function getJenisRiwayatStatus()
    {
        return [
            KegiatanStatus::SETUJU => self::SETUJU,
            KegiatanStatus::KONSEP => self::KONSEP,
            KegiatanStatus::PERIKSA => self::PERIKSA,
            KegiatanStatus::TOLAK => self::TOLAK,
        ];
    }
}
