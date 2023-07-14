<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kegiatan_harian_realisasi".
 *
 * @property int $id
 * @property int $id_kegiatan_harian
 * @property string $tanggal
 * @property string $uraian
 * @property int $kuantitas
 * @property string $jam_mulai
 * @property string $jam_selesai
 * @property string $berkas
 * @property string $kode_kegiatan_realisasi_status
 * @property int $id_pegawai_penyetuju
 * @property string $waktu_disetujui
 *
 * @property KegiatanHarian $kegiatanHarian
 * @property RefKegiatanRealisasiStatus $kodeKegiatanRealisasiStatus
 * @property Pegawai $pegawaiPenyetuju
 */
class KegiatanHarianRealisasi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kegiatan_harian_realisasi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_kegiatan_harian'], 'required'],
            [['id_kegiatan_harian', 'kuantitas', 'id_pegawai_penyetuju'], 'integer'],
            [['tanggal', 'jam_mulai', 'jam_selesai', 'waktu_disetujui'], 'safe'],
            [['uraian'], 'string'],
            [['berkas'], 'string', 'max' => 255],
            [['kode_kegiatan_realisasi_status'], 'string', 'max' => 10],
            [['id_kegiatan_harian'], 'exist', 'skipOnError' => true, 'targetClass' => KegiatanHarian::className(), 'targetAttribute' => ['id_kegiatan_harian' => 'id']],
            [['kode_kegiatan_realisasi_status'], 'exist', 'skipOnError' => true, 'targetClass' => RefKegiatanRealisasiStatus::className(), 'targetAttribute' => ['kode_kegiatan_realisasi_status' => 'kode_kegiatan_realisasi_status']],
            [['id_pegawai_penyetuju'], 'exist', 'skipOnError' => true, 'targetClass' => Pegawai::className(), 'targetAttribute' => ['id_pegawai_penyetuju' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_kegiatan_harian' => 'Kegiatan Harian',
            'tanggal' => 'Tanggal',
            'uraian' => 'Uraian',
            'kuantitas' => 'Kuantitas',
            'jam_mulai' => 'Jam Mulai',
            'jam_selesai' => 'Jam Selesai',
            'berkas' => 'Berkas',
            'kode_kegiatan_realisasi_status' => 'Kode Kegiatan Realisasi Status',
            'id_pegawai_penyetuju' => 'Id Pegawai Penyetuju',
            'waktu_disetujui' => 'Waktu Disetujui',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKegiatanHarian()
    {
        return $this->hasOne(KegiatanHarian::className(), ['id' => 'id_kegiatan_harian']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKodeKegiatanRealisasiStatus()
    {
        return $this->hasOne(RefKegiatanRealisasiStatus::className(), ['kode_kegiatan_realisasi_status' => 'kode_kegiatan_realisasi_status']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPegawaiPenyetuju()
    {
        return $this->hasOne(Pegawai::className(), ['id' => 'id_pegawai_penyetuju']);
    }
}
