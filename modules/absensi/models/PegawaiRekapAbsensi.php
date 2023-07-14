<?php

namespace app\modules\absensi\models;

use app\models\User;
use Yii;
use app\models\Pegawai;

/**
 * This is the model class for table "pegawai_rekap_absensi".
 *
 * @property int $id
 * @property int $id_pegawai
 * @property int $bulan
 * @property string $tahun
 * @property int $id_instansi
 * @property int $id_golongan
 * @property int $jumlah_hari_kerja
 * @property int $jumlah_hadir
 * @property int $jumlah_tidak_hadir
 * @property int $jumlah_izin
 * @property int $jumlah_sakit
 * @property int $jumlah_cuti
 * @property int $jumlah_tugas_belajar
 * @property int $jumlah_tugas_kedinasan
 * @property int $jumlah_dinas_luar
 * @property int $jumlah_tanpa_keterangan
 * @property int $jumlah_tidak_hadir_apel_pagi
 * @property int $jumlah_tidak_hadir_apel_sore
 * @property int $jumlah_tidak_hadir_upacara
 * @property int $jumlah_tidak_hadir_senam
 * @property int $jumlah_tidak_hadir_sidak
 * @property string $persen_potongan_fingerprint
 * @property string $persen_potongan_kegiatan
 * @property string $persen_potongan_total
 * @property int $status_kunci
 * @property string $waktu_diperbarui
 * @property int $jumlah_diklat [int(11)]
 * @property int $jumlah_alasan_teknis [int(11)]
 * @property int $jumlah_ketidakhadiran_jam_tanpa_keterangan [int(11)]
 */
class PegawaiRekapAbsensi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    public $nama_pegawai;

    public static function tableName()
    {
        return 'pegawai_rekap_absensi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_pegawai', 'bulan', 'tahun'], 'required'],
            [['id_pegawai','bulan','tahun'],'unique','targetAttribute' => [
                'id_pegawai','bulan','tahun'
            ]],
            [['id_pegawai', 'bulan', 'id_instansi', 'id_golongan', 'jumlah_hari_kerja',
                'jumlah_hadir', 'jumlah_tidak_hadir', 'jumlah_izin', 'jumlah_sakit', 'jumlah_cuti',
                'jumlah_tugas_belajar', 'jumlah_tugas_kedinasan', 'jumlah_dinas_luar',
                'jumlah_tanpa_keterangan', 'jumlah_tidak_hadir_apel_pagi', 'jumlah_tidak_hadir_apel_sore',
                'jumlah_tidak_hadir_upacara', 'jumlah_tidak_hadir_senam', 'jumlah_tidak_hadir_sidak',
                'status_kunci','id_instansi'
            ], 'integer'],
            [['tahun', 'waktu_diperbarui'], 'safe'],
            [['persen_potongan_fingerprint', 'persen_potongan_kegiatan', 'persen_potongan_total'], 'number'],
            [['persen_hadir', 'persen_tidak_hadir', 'persen_tanpa_keterangan'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_pegawai' => 'Id Pegawai',
            'bulan' => 'Bulan',
            'tahun' => 'Tahun',
            'id_instansi' => 'Id Instansi',
            'id_golongan' => 'Id Golongan',
            'jumlah_hari_kerja' => 'Jumlah Hari Kerja',
            'jumlah_hadir' => 'Jumlah Hadir',
            'jumlah_tidak_hadir' => 'Jumlah Tidak Hadir',
            'jumlah_izin' => 'Jumlah Izin',
            'jumlah_sakit' => 'Jumlah Sakit',
            'jumlah_cuti' => 'Jumlah Cuti',
            'jumlah_tugas_belajar' => 'Jumlah Tugas Belajar',
            'jumlah_tugas_kedinasan' => 'Jumlah Tugas Kedinasan',
            'jumlah_dinas_luar' => 'Jumlah Dinas Luar',
            'jumlah_tanpa_keterangan' => 'Jumlah Tanpa Keterangan',
            'jumlah_tidak_hadir_apel_pagi' => 'Jumlah Tidak Hadir Apel Pagi',
            'jumlah_tidak_hadir_apel_sore' => 'Jumlah Tidak Hadir Apel Sore',
            'jumlah_tidak_hadir_upacara' => 'Jumlah Tidak Hadir Upacara',
            'jumlah_tidak_hadir_senam' => 'Jumlah Tidak Hadir Senam',
            'jumlah_tidak_hadir_sidak' => 'Jumlah Tidak Hadir Sidak',
            'persen_potongan_fingerprint' => 'Persen Potongan Fingerprint',
            'persen_potongan_kegiatan' => 'Persen Potongan Kegiatan',
            'persen_potongan_total' => 'Persen Potongan Total',
            'status_kunci' => 'Status Kunci',
            'waktu_diperbarui' => 'Waktu Diperbarui',
        ];
    }

    public static function find()
    {
        $query = parent::find();

        if(PHP_SAPI!="cli" AND User::isInstansi()) {
            $query->andWhere([
               'pegawai_rekap_absensi.id_instansi'=>User::getIdInstansi()
            ]);
        }

        return $query;
    }

    public function getPegawai()
    {
        return $this->hasOne(Pegawai::className(),['id'=>'id_pegawai']);
    }

    public function getIsTerkunci()
    {
        return $this->status_kunci === 1;
    }

    public function getJumlahHadir()
    {
        return ($jumlahHadir = $this->jumlah_hadir) >= 0 ? $jumlahHadir : 0;
    }

    public function getJumlahCuti()
    {
        return $this->jumlah_izin + $this->jumlah_sakit + $this->jumlah_cuti;
    }
}
