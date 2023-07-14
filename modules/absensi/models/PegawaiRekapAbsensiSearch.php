<?php

namespace app\modules\absensi\models;

use app\components\Helper;
use Yii;
use app\models\Pegawai;
use app\models\User;
use app\modules\absensi\models\KetidakhadiranJenis;
use app\modules\absensi\models\PegawaiRekapAbsensi;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PegawaiRekapAbsensiSearch represents the model behind the search form of `app\modules\absensi\models\PegawaiRekapAbsensi`.
 */
class PegawaiRekapAbsensiSearch extends PegawaiRekapAbsensi
{
    public $id_ketidakhadiran_jenis;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_pegawai', 'bulan', 'id_instansi', 'id_golongan', 'jumlah_hari_kerja', 'jumlah_hadir', 'jumlah_tidak_hadir', 'jumlah_izin', 'jumlah_sakit', 'jumlah_cuti', 'jumlah_tugas_belajar', 'jumlah_tugas_kedinasan', 'jumlah_dinas_luar', 'jumlah_tanpa_keterangan', 'jumlah_tidak_hadir_apel_pagi', 'jumlah_tidak_hadir_apel_sore', 'jumlah_tidak_hadir_upacara', 'jumlah_tidak_hadir_senam', 'jumlah_tidak_hadir_sidak', 'status_kunci'], 'integer'],
            [['tahun', 'waktu_diperbarui','nama_pegawai', 'id_ketidakhadiran_jenis'], 'safe'],
            [['persen_potongan_fingerprint', 'persen_potongan_kegiatan', 'persen_potongan_total'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return \yii\db\ActiveQuery
     */

    public function getQuerySearch($params, $id_ketidakhadiran_jenis = null)
    {
        $this->load($params);

        $query = PegawaiRekapAbsensi::find();
        $query->joinWith(['pegawai']);

        $this->tahun = User::getTahun();

        if($this->bulan==null) {
            $this->bulan=1;
        }

        $query->andFilterWhere([
            'pegawai_rekap_absensi.id' => $this->id,
            'pegawai_rekap_absensi.id_pegawai' => $this->id_pegawai,
            'pegawai_rekap_absensi.id_instansi'=>$this->id_instansi,
            'pegawai_rekap_absensi.bulan' => $this->bulan,
            'pegawai_rekap_absensi.tahun' => $this->tahun,
            'pegawai_rekap_absensi.id_golongan' => $this->id_golongan,
            'pegawai_rekap_absensi.jumlah_hari_kerja' => $this->jumlah_hari_kerja,
            'pegawai_rekap_absensi.jumlah_hadir' => $this->jumlah_hadir,
            'pegawai_rekap_absensi.jumlah_tidak_hadir' => $this->jumlah_tidak_hadir,
            'pegawai_rekap_absensi.jumlah_izin' => $this->jumlah_izin,
            'pegawai_rekap_absensi.jumlah_sakit' => $this->jumlah_sakit,
            'pegawai_rekap_absensi.jumlah_cuti' => $this->jumlah_cuti,
            'pegawai_rekap_absensi.jumlah_tugas_belajar' => $this->jumlah_tugas_belajar,
            'pegawai_rekap_absensi.jumlah_tugas_kedinasan' => $this->jumlah_tugas_kedinasan,
            'pegawai_rekap_absensi.jumlah_dinas_luar' => $this->jumlah_dinas_luar,
            'pegawai_rekap_absensi.jumlah_tanpa_keterangan' => $this->jumlah_tanpa_keterangan,
            'pegawai_rekap_absensi.jumlah_tidak_hadir_apel_pagi' => $this->jumlah_tidak_hadir_apel_pagi,
            'pegawai_rekap_absensi.jumlah_tidak_hadir_apel_sore' => $this->jumlah_tidak_hadir_apel_sore,
            'pegawai_rekap_absensi.jumlah_tidak_hadir_upacara' => $this->jumlah_tidak_hadir_upacara,
            'pegawai_rekap_absensi.jumlah_tidak_hadir_senam' => $this->jumlah_tidak_hadir_senam,
            'pegawai_rekap_absensi.jumlah_tidak_hadir_sidak' => $this->jumlah_tidak_hadir_sidak,
            'pegawai_rekap_absensi.persen_potongan_fingerprint' => $this->persen_potongan_fingerprint,
            'pegawai_rekap_absensi.persen_potongan_kegiatan' => $this->persen_potongan_kegiatan,
            'pegawai_rekap_absensi.persen_potongan_total' => $this->persen_potongan_total,
            'pegawai_rekap_absensi.status_kunci' => $this->status_kunci,
            'pegawai_rekap_absensi.waktu_diperbarui' => $this->waktu_diperbarui,
        ]);

        if ($this->id_ketidakhadiran_jenis !== null) {
            switch ($this->id_ketidakhadiran_jenis) {
                case (KetidakhadiranJenis::IZIN):
                    $atribut = 'jumlah_izin';
                    break;
                case (KetidakhadiranJenis::SAKIT):
                    $atribut = 'jumlah_sakit';
                    break;
                case (KetidakhadiranJenis::CUTI):
                    $atribut = 'jumlah_cuti';
                    break;
                case (KetidakhadiranJenis::DINAS_LUAR):
                    $atribut = 'jumlah_dinas_luar';
                    break;
                case (KetidakhadiranJenis::TUGAS_BELAJAR):
                    $atribut = 'jumlah_tugas_belajar';
                    break;
                case (KetidakhadiranJenis::TUGAS_KEDINASAN):
                    $atribut = 'jumlah_tugas_kedinasan';
                    break;
                case (KetidakhadiranJenis::ALASAN_TEKNIS):
                    $atribut = 'jumlah_alasan_teknis';
                    break;
                case (KetidakhadiranJenis::TANPA_KETERANGAN):
                    $atribut = 'jumlah_tanpa_keterangan';
                    break;
            }
            $query->andWhere(['!=', $atribut, 0])
                ->andWhere("$atribut IS NOT NULL");
        }

        $query->andFilterWhere(['like', 'pegawai.nama', $this->nama_pegawai]);

        return $query;
    }

    public function search($params)
    {
        $query = $this->getQuerySearch($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
                'pageSize'=>10
            ]
        ]);

        return $dataProvider;
    }

    public function findAllPegawaiByIdInstansi($updatePegawaiRekap = false)
    {
        $query = Pegawai::find();
        $query->aktif()
            ->andWhere([
                'id_instansi'=>$this->id_instansi,
            ]);

        if ($updatePegawaiRekap) {
            $bulan = $this->bulan;
            // TODO
        }

        return $query->all();
    }

    public function getInstansi()
    {
        return $this->hasOne(\app\models\Instansi::class, ['id' => 'id_instansi']);
    }

    public function getBulanLengkapTahun()
    {
        $output = User::getTahun();

        if ($this->bulan != null) {
            $output = Helper::getBulanLengkap($this->bulan) . ' ' . User::getTahun();
        }

        return $output;
    }
}
