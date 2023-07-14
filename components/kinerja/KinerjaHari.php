<?php

namespace app\components\kinerja;

use app\models\InstansiPegawai;
use app\models\Pengaturan;
use app\models\PengaturanBerlaku;
use app\modules\absensi\models\PegawaiDispensasiJenis;
use app\modules\kinerja\models\KegiatanHarian;
use app\modules\kinerja\models\KegiatanHarianJenis;
use app\modules\kinerja\models\KegiatanStatus;
use DateTime;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii2mod\query\ArrayQuery;

class KinerjaHari extends BaseKinerja
{
    /**
     * @var DateTime
     */
    protected $_date;

    public $tanggal;

    /**
     * @var KinerjaBulan
     */
    public $kinerjaBulan;

    private $_arrayKegiatanHarian;

    private $_jumlahKegiatanHarian;

    private $_persenPotongan;

    private $_nilaiPotongan;

    private $_isHariKerja;

    public function getDate()
    {
        if ($this->_date !== null) {
            return $this->_date;
        }

        $this->_date = DateTime::createFromFormat('Y-m-d', $this->tanggal);

        return $this->_date;
    }

    /**
     * @throws InvalidConfigException
     */
    public function execute()
    {
        if ($this->tanggal === null) {
            throw new InvalidConfigException('The "tanggal" property must be set.');
        }

        if ($this->kinerjaBulan === null) {
            throw new InvalidConfigException('The "kinerjaBulan" property must be set.');
        }
    }

    public function getJumlahKegiatanHarian()
    {
        if ($this->_jumlahKegiatanHarian !== null) {
            return $this->_jumlahKegiatanHarian;
        }

        $date = $this->getDate();

        $query = new ArrayQuery();
        $query->from($this->kinerjaBulan->getArrayKegiatanHarian());
        $query->andWhere(['tanggal' => $this->tanggal]);
        $query->andWhere(['>=', 'waktu_dibuat', $date->format('Y-m-d 06:00:00')]);
        $query->andWhere(['<=', 'waktu_dibuat', $date->format('Y-m-d 23:59:59')]);

        $this->_jumlahKegiatanHarian = $query->count();

        return $this->_jumlahKegiatanHarian;
    }

    private $_jumlahKegiatanHarianWaktuNull;
    public function getJumlahKegiatanHarianWaktuNull()
    {
        if ($this->_jumlahKegiatanHarianWaktuNull !== null) {
            return $this->_jumlahKegiatanHarianWaktuNull;
        }

        $query = new ArrayQuery();
        $query->from($this->kinerjaBulan->getArrayKegiatanHarian());
        $query->andWhere(['tanggal' => $this->tanggal]);
        $query->andWhere(['waktu_dibuat' => null]);

        $this->_jumlahKegiatanHarianWaktuNull = $query->count();

        return $this->_jumlahKegiatanHarianWaktuNull;
    }

    public function getJumlahKegiatanHarianKonsep()
    {
        $query = new ArrayQuery();
        $query->from($this->kinerjaBulan->getArrayKegiatanHarian());
        $query->andWhere(['tanggal' => $this->tanggal]);
        $query->andWhere(['id_kegiatan_status' => KegiatanStatus::KONSEP]);

        return $query->count();
    }

    public function getJumlahKegiatanHarianSetuju()
    {
        $query = new ArrayQuery();
        $query->from($this->kinerjaBulan->getArrayKegiatanHarian());
        $query->andWhere(['tanggal' => $this->tanggal]);
        $query->andWhere(['id_kegiatan_status' => KegiatanStatus::SETUJU]);

        return $query->count();
    }

    public function getJumlahKegiatanHarianPeriksa()
    {
        $query = new ArrayQuery();
        $query->from($this->kinerjaBulan->getArrayKegiatanHarian());
        $query->andWhere(['tanggal' => $this->tanggal]);
        $query->andWhere(['id_kegiatan_status' => KegiatanStatus::PERIKSA]);

        return $query->count();
    }

    public function getJumlahKegiatanHarianTolak()
    {
        $query = new ArrayQuery();
        $query->from($this->kinerjaBulan->getArrayKegiatanHarian());
        $query->andWhere(['tanggal' => $this->tanggal]);
        $query->andWhere(['id_kegiatan_status' => KegiatanStatus::TOLAK]);

        return $query->count();
    }

    public function getPersenPotongan()
    {
        if ($this->_persenPotongan !== null) {
            return $this->_persenPotongan;
        }

        if ($this->isPegawaiTidakWajibCkhp()) {
            $this->_persenPotongan = 0;
            return $this->_persenPotongan;
        }

        if ($this->isMulaiPengisianCkhp() == false) {
            $this->_persenPotongan = 0;
            return $this->_persenPotongan;
        }

        if ($this->isMasaDepan()) {
            $this->_persenPotongan = 0;
            return $this->_persenPotongan;
        }

        if ($this->getJumlahKetidakhadiranPanjang() > 0) {
            $this->_persenPotongan = 0;
            return $this->_persenPotongan;
        }

        if ($this->isHariKerja() == false) {
            $this->_persenPotongan = 0;
            return $this->_persenPotongan;
        }

        /*
        if ($this->isKegiatanHarianDiskresi()) {
            $this->_persenPotongan = 0;
            return $this->_persenPotongan;
        }
        */

        if ($this->getJumlahKegiatanHarianWaktuNull() > 0) {
            $this->_persenPotongan = 0;
            return $this->_persenPotongan;
        }

        if ($this->getJumlahKegiatanHarian() > 0) {
            $this->_persenPotongan = 0;
            return $this->_persenPotongan;
        }

        $this->_persenPotongan = $this->getNilaiPotongan();
        return $this->_persenPotongan;
    }

    public function getNilaiPotongan(): float
    {
        if ($this->_nilaiPotongan !== null) {
            return $this->_nilaiPotongan;
        }

        $pengaturan = Pengaturan::findOne(Pengaturan::PERSEN_POTONGAN_CKHP);

        if ($pengaturan == null) {
            $this->_nilaiPotongan = 0;
            return $this->_nilaiPotongan;
        }

        $query = PengaturanBerlaku::find();
        $query->andWhere(['id_pengaturan' => $pengaturan->id]);
        $query->andWhere('tanggal_mulai <= :tanggal AND tanggal_selesai >= :tanggal', [
            ':tanggal' => $this->tanggal,
        ]);

        /* @var $pengaturanBerlaku PengaturanBerlaku */
        $pengaturanBerlaku = $query->one();

        if ($pengaturanBerlaku == null) {
            $this->_nilaiPotongan = 0;
            return $this->_nilaiPotongan;
        }

        $this->_nilaiPotongan = $pengaturanBerlaku->nilai;

        return $this->_nilaiPotongan;
    }

    public function isMasaDepan()
    {
        return $this->tanggal > date('Y-m-d');
    }

    public function isHariKerja()
    {
        if ($this->_isHariKerja !== null) {
            return $this->_isHariKerja;
        }

        $date = $this->getDate();

        $shiftKerja = $this->kinerjaBulan->pegawai->findShiftKerja([
            'tanggal' => $this->tanggal,
        ]);

        if ($shiftKerja == null) {
            $this->_isHariKerja = false;
            return $this->_isHariKerja;
        }

        if ($this->isHariLibur() && $shiftKerja->getIsLiburNasional()) {
            $this->_isHariKerja = false;
            return $this->_isHariKerja;
        }

        // Jika tidak ada jam kerja pada tanggal
        if ($shiftKerja->countJamKerja($date->format('N')) == 0) {
            $this->_isHariKerja = false;
            return $this->_isHariKerja;
        }

        if ($this->isPegawaiDispensasi() == true) {
            $this->_isHariKerja = false;
            return $this->_isHariKerja;
        }

        if ($this->isCuti()) {
            $this->_isHariKerja = false;
            return $this->_isHariKerja;
        }

        $this->_isHariKerja = true;
        return $this->_isHariKerja;
    }

    private $_isHariLibur;
    public function isHariLibur(): bool
    {
        if ($this->_isHariLibur !== null) {
            return $this->_isHariLibur;
        }

        $this->_isHariLibur = $this->kinerjaBulan->pegawai->isHariLibur([
            'tanggal' => $this->tanggal,
        ]);

        return $this->_isHariLibur;
    }

    private $_isPegawaiDispensasi;
    public function isPegawaiDispensasi(): bool
    {
        if ($this->_isPegawaiDispensasi !== null) {
            return $this->_isPegawaiDispensasi;
        }

        $this->_isPegawaiDispensasi = $this->kinerjaBulan->pegawai->isPegawaiDispensasi([
            'tanggal' => $this->tanggal,
            'id_pegawai_dispensasi_jenis' => [
                PegawaiDispensasiJenis::FULL,
                PegawaiDispensasiJenis::CKHP
            ],
        ]);

        return $this->_isPegawaiDispensasi;
    }

    private $_isCuti;
    public function isCuti(): bool
    {
        if ($this->_isCuti !== null) {
            return $this->_isCuti;
        }

        $this->_isCuti = $this->kinerjaBulan->pegawai->hasCuti([
            'tanggal' => $this->tanggal,
        ]);

        return $this->_isCuti;
    }

    private $_jumlahKetidakhadiranPanjang;
    public function getJumlahKetidakhadiranPanjang()
    {
        if ($this->_jumlahKetidakhadiranPanjang !== null ) {
            return $this->_jumlahKetidakhadiranPanjang;
        }

        $query = new ArrayQuery();
        $query->from($this->kinerjaBulan->getArrayKetidakhadiranPanjang());
        $query->andWhere(['<=', 'tanggal_mulai', $this->tanggal]);
        $query->andWhere(['>=', 'tanggal_selesai', $this->tanggal]);

        $this->_jumlahKetidakhadiranPanjang = $query->count();

        return $this->_jumlahKetidakhadiranPanjang;
    }

    private $_isMulaiPengisianCkhp;
    public function isMulaiPengisianCkhp()
    {
        if ($this->_isMulaiPengisianCkhp !== null) {
            return $this->_isMulaiPengisianCkhp;
        }

        if ($this->tanggal < '2022-06-01') {
            $this->_isMulaiPengisianCkhp = false;
            return $this->_isMulaiPengisianCkhp;
        }

        $query = InstansiPegawai::query();
        $query->andWhere(['id_pegawai' => $this->kinerjaBulan->pegawai->id]);
        $query->andWhere('tanggal_berlaku <= :tanggal AND tanggal_selesai >= :tanggal', [
            ':tanggal' => $this->tanggal,
        ]);

        if ($query->count() == 0) {
            $this->_isMulaiPengisianCkhp = false;
            return $this->_isMulaiPengisianCkhp;
        }

        $this->_isMulaiPengisianCkhp = true;
        return $this->_isMulaiPengisianCkhp;
    }

    private $_isPegawaiTidakWajibCkhp;
    public function isPegawaiTidakWajibCkhp()
    {
        if ($this->_isPegawaiTidakWajibCkhp !== null) {
            return $this->_isPegawaiTidakWajibCkhp;
        }

        $this->_isPegawaiTidakWajibCkhp = $this->kinerjaBulan->pegawai->isPegawaiTidakWajibCkhp([
            'tanggal' => $this->tanggal,
        ]);

        return $this->_isPegawaiTidakWajibCkhp;
    }

    private $_isKegiatanHarianDiskresi;
    public function isKegiatanHarianDiskresi()
    {
        if ($this->_isKegiatanHarianDiskresi !== null) {
            return $this->_isKegiatanHarianDiskresi;
        }

        $query = new ArrayQuery();
        $query->from($this->kinerjaBulan->getArrayKegiatanHarianDiskresi());
        $query->andWhere(['tanggal' => $this->tanggal]);

        $jumlah = $query->count();

        $this->_isKegiatanHarianDiskresi = false;

        if ($jumlah > 0) {
            $this->_isKegiatanHarianDiskresi = true;
        }

        return $this->_isKegiatanHarianDiskresi;
    }

    public function getStringPersenPotongan()
    {
        if ($this->isMasaDepan()) {
            return null;
        }

        if ($this->isHariKerja() == false) {
            return null;
        }

        return $this->getPersenPotongan();
    }

    /**
     * @param array $params
     * @return string
     */
    public function getLinkCreateKegiatanHarianIcon(array $params = [])
    {
        $id_kegiatan_harian_jenis = $params['id_kegiatan_harian_jenis'];
        $class = 'fa fa-plus-square';

        if ($id_kegiatan_harian_jenis == KegiatanHarianJenis::TAMBAHAN) {
            $class = 'fa fa-plus-square-o';
        }

        $canAccess = $this->kinerjaBulan->pegawai->canAccessKegiatanHarian([
            'tanggal' => $this->tanggal,
        ]);

        if ($canAccess == false) {
            return null;
        }

        return Html::a('<i class="'. $class .'"></i>', [
            '/kinerja/kegiatan-harian/create-v4',
            'id_kegiatan_harian_jenis' => $id_kegiatan_harian_jenis,
            'tanggal' => $this->tanggal,
        ]);
    }
}
