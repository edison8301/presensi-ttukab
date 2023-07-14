<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kegiatan_realisasi".
 *
 * @property int $id
 * @property int $id_kegiatan
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
 * @property Kegiatan $kegiatan
 * @property Pegawai $pegawaiPenyetuju
 * @property RefKegiatanRealisasiStatus $kodeKegiatanRealisasiStatus
 */
class KegiatanRealisasi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kegiatan_realisasi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_kegiatan'], 'required'],
            [['id_kegiatan', 'kuantitas', 'id_pegawai_penyetuju'], 'integer'],
            [['tanggal', 'jam_mulai', 'jam_selesai', 'waktu_disetujui'], 'safe'],
            [['uraian'], 'string'],
            [['berkas'], 'string', 'max' => 255],
            [['kode_kegiatan_realisasi_status'], 'string', 'max' => 10],
            [['id_kegiatan'], 'exist', 'skipOnError' => true, 'targetClass' => Kegiatan::className(), 'targetAttribute' => ['id_kegiatan' => 'id']],
            [['id_pegawai_penyetuju'], 'exist', 'skipOnError' => true, 'targetClass' => Pegawai::className(), 'targetAttribute' => ['id_pegawai_penyetuju' => 'id']],
            [['kode_kegiatan_realisasi_status'], 'exist', 'skipOnError' => true, 'targetClass' => RefKegiatanRealisasiStatus::className(), 'targetAttribute' => ['kode_kegiatan_realisasi_status' => 'kode_kegiatan_realisasi_status']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_kegiatan' => 'Id Kegiatan',
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
    public function getKegiatan()
    {
        return $this->hasOne(Kegiatan::className(), ['id' => 'id_kegiatan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPegawaiPenyetuju()
    {
        return $this->hasOne(Pegawai::className(), ['id' => 'id_pegawai_penyetuju']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRefKegiatanRealisasiStatus()
    {
        return $this->hasOne(RefKegiatanRealisasiStatus::className(), ['kode_kegiatan_realisasi_status' => 'kode_kegiatan_realisasi_status']);
    }

    public function getWaktu()
    {
        return "$this->jam_mulai - $this->jam_selesai";
    }

    public function getNamaKegiatan()
    {
        return $this->getRelationField("kegiatan", "nama_kegiatan");
    }

    public function getRelationField($relation, $field)
    {
        return $this->$relation !== null ? $this->$relation->$field : null;
    }
}
