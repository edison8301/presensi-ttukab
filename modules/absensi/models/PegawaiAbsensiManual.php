<?php

namespace app\modules\absensi\models;

use app\components\Session;
use app\models\Pegawai;
use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "pegawai_absensi_manual".
 *
 * @property int $id
 * @property int $id_pegawai
 * @property string $tanggal_mulai
 * @property string $tanggal_selesai
 * @property int $status_hapus
 * @property string $waktu_hapus
 * @property int $id_user_hapus
 * @property int $status
 * @property Pegawai $pegawai
 */
class PegawaiAbsensiManual extends \yii\db\ActiveRecord
{
    const AKTIF = 1;
    const TIDAK_AKTIF = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pegawai_absensi_manual';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pegawai', 'tanggal_mulai'], 'required'],
            [['id_pegawai', 'status_hapus', 'id_user_hapus', 'status'], 'integer'],
            [['tanggal_mulai', 'tanggal_selesai', 'waktu_hapus'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_pegawai' => 'Id Pegawai',
            'tanggal_mulai' => 'Tanggal Mulai',
            'tanggal_selesai' => 'Tanggal Selesai',
            'status_hapus' => 'Status Hapus',
            'waktu_hapus' => 'Waktu Hapus',
            'id_user_hapus' => 'Id User Hapus',
            'namaPegawai' => 'Nama Pegawai',
            'nipPegawai' => 'NIP Pegawai'
        ];
    }

    /**
     * {@inheritdoc}
     * @return PegawaiAbsensiManualQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PegawaiAbsensiManualQuery(get_called_class());
    }

    public function getPegawai()
    {
        return $this->hasOne(Pegawai::class,['id'=>'id_pegawai']);
    }

    public function getNamaPegawai()
    {
        return @$this->pegawai->nama;
    }

    public function getNipPegawai()
    {
        return @$this->pegawai->nip;
    }

    public static function getListStatus()
    {
        return [
            static::AKTIF => 'Aktif',
            static::TIDAK_AKTIF => 'Tidak Aktif',
        ];
    }

    public function getLabelStatus()
    {
        if ($this->status == static::AKTIF) {
            return Html::tag('label', 'Aktif', ['class' => 'label label-success']);
        }

        if ($this->status == static::TIDAK_AKTIF) {
            return Html::tag('label', 'Tidak Aktif', ['class' => 'label label-danger']);
        }
    }

    public function setTanggalSelesai()
    {
        if ($this->tanggal_selesai == null) {
            $this->tanggal_selesai = '9999-12-31';
        }

        return $this->tanggal_selesai;
    }
}
