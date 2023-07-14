<?php

namespace app\modules\tukin\models;

use app\models\InstansiPegawai;
use app\models\Pegawai;
use app\models\PegawaiJenis;
use app\models\PegawaiNonTpp;
use app\models\PegawaiNonTppJenis;
use app\models\Pengaturan;
use app\models\RekapJenis;
use app\models\RekapPegawaiBulan;
use app\modules\absensi\models\KetidakhadiranPanjang;
use app\modules\absensi\models\KetidakhadiranPanjangJenis;
use app\modules\absensi\models\KetidakhadiranPanjangStatus;
use DateTime;
use yii2mod\query\ArrayQuery;
use yii\base\Exception;

/**
 *
 * @property int $id_peegawai
 * @property Pegawai $pegawai
 */
class PegawaiTukin extends \yii\base\Model
{
    public $id_pegawai;

    public $tahun;

    public $bulan;

    public $besaran_tpp;

    private $_date;

    private $_pegawai;

    private $_instansiPegawai;

    private $_instansiPegawaiPlt;

    private $_arrayKetidakhadiranPanjang;

    private $_arrayInstansiPegawai;

    private $_arrayPegawaiNonTpp;

    private $_persenDibayarCutiAlasanPenting;

    private $_persenDibayarCutiSakit;

    private $_persenPotonganIndeksProfAsn;

    private $_jumlahHariKetidakhadiranPanjang;

    private $_skorMinimalIpAsn;

    private $_isPegawaiCutiBesarNonTpp;

    private $_isPegawaiTugasBelajarNonTpp;

    public $statusUpdateInstansiPegawai = false;

    public $statusUpdateInstansiPegawaiPlt = false;

    public function __construct(array $params)
    {
        $this->id_pegawai = $params['id_pegawai'];
        $this->tahun = $params['tahun'];
        $this->bulan = $params['bulan'];
    }

    public function getDate()
    {
        if ($this->_date !== null) {
            return $this->_date;
        }

        $datetime = \DateTime::createFromFormat('Y-n-d', $this->tahun . '-' . $this->bulan .'-01');

        $this->_date = $datetime;

        return $this->_date;
    }

    public function getPegawai()
    {
        if ($this->_pegawai !== null) {
            return $this->_pegawai;
        }

        $pegawai = Pegawai::findOne($this->id_pegawai);

        $this->_pegawai = $pegawai;

        return $this->_pegawai;
    }

    public function getArrayKetidakhadiranPanjang()
    {
        if ($this->_arrayKetidakhadiranPanjang !== null) {
            return $this->_arrayKetidakhadiranPanjang;
        }

        $query = KetidakhadiranPanjang::find();
        $query->andWhere(['id_pegawai' => $this->id_pegawai]);
        $query->andWhere('tanggal_mulai <= :tanggal_akhir AND tanggal_selesai >= :tanggal_awal', [
            'tanggal_awal' => $this->date->format('Y-m-01'),
            'tanggal_akhir' => $this->date->format('Y-m-t'),
        ]);

        $this->_arrayKetidakhadiranPanjang = $query->all();

        return $this->_arrayKetidakhadiranPanjang;
    }

    public function getArrayInstansiPegawai()
    {
        if ($this->_arrayInstansiPegawai !== null) {
            return $this->_arrayInstansiPegawai;
        }

        $query = InstansiPegawai::find();
        $query->andWhere(['id_pegawai' => $this->id_pegawai]);

        $this->_arrayInstansiPegawai = $query->all();

        return $this->_arrayInstansiPegawai;
    }

    public function getArrayPegawaiNonTpp()
    {
        if ($this->_arrayPegawaiNonTpp !== null) {
            return $this->_arrayPegawaiNonTpp;
        }

        $query = PegawaiNonTpp::find();
        $query->andWhere(['id_pegawai' => $this->id_pegawai]);
        $query->andWhere('tanggal_mulai <= :tanggal_akhir AND tanggal_selesai >= :tanggal_awal', [
            'tanggal_awal' => $this->date->format('Y-m-01'),
            'tanggal_akhir' => $this->date->format('Y-m-t'),
        ]);

        $this->_arrayPegawaiNonTpp = $query->all();

        return $this->_arrayPegawaiNonTpp;
    }

    public function getInstansiPegawai()
    {
        if ($this->statusUpdateInstansiPegawai === true) {
            return $this->_instansiPegawai;
        }

        $tanggal = $this->date->format('Y-m-15');

        $query = new ArrayQuery();
        $query->from($this->getArrayInstansiPegawai());
        $query->andWhere(['<=', 'tanggal_mulai', $tanggal]);
        $query->andWhere(['>=', 'tanggal_selesai', $tanggal]);
        $query->andWhere(['status_plt' => 0]);

        $instansiPegawai = $query->one();

        if ($instansiPegawai == false) { // ubah return false ke null karena menggunakan ArrayQuery
            $instansiPegawai = null;
        }

        $this->_instansiPegawai = $instansiPegawai;
        $this->statusUpdateInstansiPegawai = true;
        return $this->_instansiPegawai;
    }

    public function setInstansiPegawaiFromParam($instansiPegawai)
    {
        $this->_instansiPegawai = $instansiPegawai;
    }

    public function getInstansiPegawaiPlt()
    {
        if ($this->statusUpdateInstansiPegawaiPlt === true) {
            return $this->_instansiPegawaiPlt;
        }

        $tanggal = $this->date->format('Y-m-15');

        $query = new ArrayQuery();
        $query->from($this->getArrayInstansiPegawai());
        $query->andWhere(['<=', 'tanggal_mulai', $tanggal]);
        $query->andWhere(['>=', 'tanggal_selesai', $tanggal]);
        $query->andWhere(['status_plt' => 1]);

        $instansiPegawaiPlt = $query->one();

        if ($instansiPegawaiPlt == false) { // ubah return false ke null karena menggunakan ArrayQuery
            $instansiPegawaiPlt = null;
        }

        $this->_instansiPegawaiPlt = $instansiPegawaiPlt;
        $this->statusUpdateInstansiPegawaiPlt = true;
        return $this->_instansiPegawaiPlt;
    }

    public function setInstansiPegawaiPltFromParam($instansiPegawaiPlt)
    {
        $this->_instansiPegawaiPlt = $instansiPegawaiPlt;
    }

    public function getJumlahHariKetidakhadiranPanjang(array $params = [])
    {
        $id_ketidakhadiran_panjang_jenis = @$params['id_ketidakhadiran_panjang_jenis'];

        if (@$this->_jumlahHariKetidakhadiranPanjang[$id_ketidakhadiran_panjang_jenis] !== null) {
            return $this->_jumlahHariKetidakhadiranPanjang[$id_ketidakhadiran_panjang_jenis];
        }

        $query = new ArrayQuery();
        $query->from($this->getArrayKetidakhadiranPanjang());
        $query->andWhere([
            'id_ketidakhadiran_panjang_status' => KetidakhadiranPanjangStatus::SETUJU,
            'id_ketidakhadiran_panjang_jenis' => $id_ketidakhadiran_panjang_jenis,
        ]);

        $hari = 0;
        
        foreach ($query->all() as $ketidakhadiranPanjang) {
            $tanggal_mulai = new DateTime($ketidakhadiranPanjang->tanggal_mulai);
            $tanggal_selesai = new DateTime($ketidakhadiranPanjang->tanggal_selesai);

            $date = $tanggal_mulai->diff($tanggal_selesai);
            $hari += ($date->days + 1);
        }

        $this->_jumlahHariKetidakhadiranPanjang[$id_ketidakhadiran_panjang_jenis] = $hari;

        return $this->_jumlahHariKetidakhadiranPanjang[$id_ketidakhadiran_panjang_jenis];
    }

    public function getPersenDibayarCutiAlasanPenting()
    {
        if ($this->_persenDibayarCutiAlasanPenting !== null) {
            return $this->_persenDibayarCutiAlasanPenting;
        }

        $pengaturan = Pengaturan::findOne(Pengaturan::PERSEN_DIBAYAR_CUTI_ALASAN_PENTING);
        $persen = null;

        if ($pengaturan !== null) {
            $persen = $pengaturan->getNilaiBerlaku([
                'tahun' => $this->tahun,
                'bulan' => $this->bulan,
            ]);
        }

        $jumlahHari = $this->getJumlahHariKetidakhadiranPanjang([
            'id_ketidakhadiran_panjang_jenis' => KetidakhadiranPanjangJenis::KETIDAKHADIRAN_CUTI_ALASAN_PENTING,
        ]);
    
        if ($jumlahHari >= 12 AND $persen !== null) {
            $this->_persenDibayarCutiAlasanPenting = $persen / 100;
            return $this->_persenDibayarCutiAlasanPenting;
        }

        $this->_persenDibayarCutiAlasanPenting = 1;
        return $this->_persenDibayarCutiAlasanPenting;
    }

    public function getPersenDibayarCutiSakit()
    {
        if ($this->_persenDibayarCutiSakit !== null) {
            return $this->_persenDibayarCutiSakit;
        }

        $pengaturan = Pengaturan::findOne(Pengaturan::PERSEN_DIBAYAR_CUTI_SAKIT);
        $persen = null;
        
        if ($pengaturan !== null) {
            $persen = $pengaturan->getNilaiBerlaku([
                'tahun' => $this->tahun,
                'bulan' => $this->bulan,
            ]);
        }

        $jumlahHari = $this->getJumlahHariKetidakhadiranPanjang([
            'id_ketidakhadiran_panjang_jenis' => KetidakhadiranPanjangJenis::KETIDAKHADIRAN_CUTI_SAKIT,
        ]);
    
        if ($jumlahHari >= 12 AND $persen !== null) {
            $this->_persenDibayarCutiSakit = $persen / 100;
            return $this->_persenDibayarCutiSakit;
        }

        $this->_persenDibayarCutiSakit = 1;
        return $this->_persenDibayarCutiSakit;
    }

    public function getPersenPotonganIndexProfAsn()
    {
        if ($this->_persenPotonganIndeksProfAsn !== null) {
            return $this->_persenPotonganIndeksProfAsn;
        }

        $pengaturan = Pengaturan::findOne(Pengaturan::PERSEN_POTONGAN_INDEKS_PROFESIONALITAS);
        $persen = 0;
        
        if ($pengaturan !== null) {
            $persen = $pengaturan->getNilaiBerlaku([
                'tahun' => $this->tahun,
                'bulan' => $this->bulan,
            ]);
        }

        if ($persen === null) {
            $persen = 0;
        }

        if ($this->getSkorIndeksIpAsn() >= $this->getSkorMinimalIpAsn()) {
            $persen = 0;
        }

        if ($this->pegawai->id_pegawai_jenis == PegawaiJenis::P3K) {
            $persen = 0;
        }

        $this->_persenPotonganIndeksProfAsn = $persen;
        return $this->_persenPotonganIndeksProfAsn;
    }

    public function getRupiahPotonganIndeksProfAsn()
    {
        $persen = $this->getPersenPotonganIndexProfAsn();

        $persen = $persen / 100;

        $rupiah = $this->besaran_tpp * $persen;

        return $rupiah;
    }

    public function getSkorIndeksIpAsn()
    {
        $rekapPegawaiBulan = RekapPegawaiBulan::findOrCreate([
            'id_pegawai' => $this->id_pegawai,
            'id_rekap_jenis' => RekapJenis::INDEKS_IP_ASN,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
        ]);

        return $rekapPegawaiBulan->nilai;
    }

    public function getSkorMinimalIpAsn()
    {
        if ($this->_skorMinimalIpAsn !== null) {
            return $this->_skorMinimalIpAsn;
        }

        $pengaturan = Pengaturan::findOne(Pengaturan::MINIMAL_SKOR_IP_ASN);
        $skor = 0;
    
        if ($pengaturan !== null) {
            $skor = $pengaturan->getNilaiBerlaku([
                'tahun' => $this->tahun,
                'bulan' => $this->bulan,
            ]);
        }

        $this->_skorMinimalIpAsn = $skor;
        return $this->_skorMinimalIpAsn;
    }

    public function isPegawaiCutiBesarNonTpp()
    {
        if ($this->_isPegawaiCutiBesarNonTpp !== null) {
            return $this->_isPegawaiCutiBesarNonTpp;
        }

        $query = new ArrayQuery();
        $query->from($this->getArrayPegawaiNonTpp());
        $query->andWhere([
            'id_pegawai_non_tpp_jenis' => PegawaiNonTppJenis::CUTI_BESAR,
        ]);

        $this->_isPegawaiCutiBesarNonTpp = $query->count() > 0;

        return $this->_isPegawaiCutiBesarNonTpp;
    }

    public function isPegawaiTugasBelajarNonTpp()
    {
        if ($this->_isPegawaiTugasBelajarNonTpp !== null) {
            return $this->_isPegawaiTugasBelajarNonTpp;
        }

        $query = new ArrayQuery();
        $query->from($this->getArrayPegawaiNonTpp());
        $query->andWhere([
            'id_pegawai_non_tpp_jenis' => PegawaiNonTppJenis::TUGAS_BELAJAR,
        ]);

        $this->_isPegawaiTugasBelajarNonTpp = $query->count() > 0;

        return $this->_isPegawaiTugasBelajarNonTpp;
    }
}
