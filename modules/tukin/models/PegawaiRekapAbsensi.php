<?php

namespace app\modules\tukin\models;

use Yii;

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
 * @property int $jumlah_diklat
 * @property int $jumlah_tanpa_keterangan
 * @property int $jumlah_alasan_teknis
 * @property int $jumlah_tidak_hadir_apel_pagi
 * @property int $jumlah_tidak_hadir_apel_sore
 * @property int $jumlah_tidak_hadir_upacara
 * @property int $jumlah_tidak_hadir_senam
 * @property int $jumlah_tidak_hadir_sidak
 * @property int $jumlah_ketidakhadiran_jam_tanpa_keterangan
 * @property string $persen_potongan_fingerprint
 * @property string $persen_potongan_kegiatan
 * @property string $persen_potongan_total
 * @property int $status_kunci
 * @property string $waktu_diperbarui
 */
class PegawaiRekapAbsensi extends \yii\db\ActiveRecord implements RekapTunjanganInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pegawai_rekap_absensi';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pegawai', 'bulan', 'tahun'], 'required'],
            [['id_pegawai', 'bulan', 'id_instansi', 'id_golongan', 'jumlah_hari_kerja', 'jumlah_hadir', 'jumlah_tidak_hadir', 'jumlah_izin', 'jumlah_sakit', 'jumlah_cuti', 'jumlah_tugas_belajar', 'jumlah_tugas_kedinasan', 'jumlah_dinas_luar', 'jumlah_diklat', 'jumlah_tanpa_keterangan', 'jumlah_alasan_teknis', 'jumlah_tidak_hadir_apel_pagi', 'jumlah_tidak_hadir_apel_sore', 'jumlah_tidak_hadir_upacara', 'jumlah_tidak_hadir_senam', 'jumlah_tidak_hadir_sidak', 'jumlah_ketidakhadiran_jam_tanpa_keterangan', 'status_kunci'], 'integer'],
            [['tahun', 'waktu_diperbarui'], 'safe'],
            [['persen_potongan_fingerprint', 'persen_potongan_kegiatan', 'persen_potongan_total'], 'number'],
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
            'jumlah_diklat' => 'Jumlah Diklat',
            'jumlah_tanpa_keterangan' => 'Jumlah Tanpa Keterangan',
            'jumlah_alasan_teknis' => 'Jumlah Alasan Teknis',
            'jumlah_tidak_hadir_apel_pagi' => 'Jumlah Tidak Hadir Apel Pagi',
            'jumlah_tidak_hadir_apel_sore' => 'Jumlah Tidak Hadir Apel Sore',
            'jumlah_tidak_hadir_upacara' => 'Jumlah Tidak Hadir Upacara',
            'jumlah_tidak_hadir_senam' => 'Jumlah Tidak Hadir Senam',
            'jumlah_tidak_hadir_sidak' => 'Jumlah Tidak Hadir Sidak',
            'jumlah_ketidakhadiran_jam_tanpa_keterangan' => 'Jumlah Ketidakhadiran Jam Tanpa Keterangan',
            'persen_potongan_fingerprint' => 'Persen Potongan Fingerprint',
            'persen_potongan_kegiatan' => 'Persen Potongan Kegiatan',
            'persen_potongan_total' => 'Persen Potongan Total',
            'status_kunci' => 'Status Kunci',
            'waktu_diperbarui' => 'Waktu Diperbarui',
        ];
    }

    public function getPotonganTotal()
    {
        return $this->persen_potongan_fingerprint;
    }

    public function getPersen()
    {
        return 100 - $this->getPotonganTotal();
    }
}
