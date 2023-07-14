<?php

namespace app\modules\kinerja\models;

use Yii;

/**
 * This is the model class for table "kegiatan_riwayat".
 *
 * @property int $id
 * @property int $id_kegiatan_jenis
 * @property int $id_kegiatan
 * @property int $id_riwayat_jenis
 * @property string $keterangan
 * @property string $waktu_dibuat
 * @property string $username_pembuat
 * @property string $nama_user
 */
class KegiatanRiwayat extends BaseRiwayat
{
    const TAHUNAN = 1;
    const BULANAN = 2;
    const HARIAN = 3;

    public static function getForeignKey()
    {
        return 'id_kegiatan';
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kegiatan_riwayat';
    }

    /**
     * @inheritdoc
     * @return KegiatanRiwayatQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new KegiatanRiwayatQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                [['id_kegiatan_jenis', 'id_kegiatan'], 'required'],
                [['id_kegiatan_jenis', 'id_kegiatan'], 'integer'],
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_kegiatan_jenis' => 'Id Kegiatan Jenis',
            'id_kegiatan' => 'Id Kegiatan',
            'id_riwayat_jenis' => 'Id Riwayat Jenis',
            'keterangan' => 'Keterangan',
            'waktu_dibuat' => 'Waktu Dibuat',
            'username_pembuat' => 'Username Pembuat',
            'nama_user' => 'Nama User',
        ];
    }

    public static function instantiate($row)
    {
        switch ($row['id_kegiatan_jenis']) {
            case self::TAHUNAN:
                return new KegiatanRiwayatTahunan();
            case self::HARIAN:
                return new KegiatanRiwayatHarian();
            default:
               return new self;
        }
    }

    public static function getKegiatanJenis()
    {
        return [self::TAHUNAN, self::BULANAN, self::HARIAN];
    }

    public function getNamaKegiatanJenis()
    {
        return @$this->getKegiatanJenis()[$this->id_kegiatan_jenis];
    }
}
