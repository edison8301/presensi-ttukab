<?php


namespace app\modules\absensi\models;


use app\models\Pegawai;
use app\models\User;
use app\modules\absensi\models\ceklis\PresensiHari;
use DateTime;
use yii\base\Component;
use yii2mod\query\ArrayQuery;
use function array_map;
use function array_sum;
use function date;

/**
 *
 * @property DateTime $date
 * @property int $jumlahHadir
 * @property int $jumlahTidakHadir
 * @property int $jumlahHariKerja
 * @property int $potongan
 * @property KetidakhadiranJamKerja[] $allKetidakhadiranJamKerja
 * @property KetidakhadiranPanjang[] $allKetidakhadiranPanjang
 * @property mixed $tahun
 */
class PresensiCeklis extends Component
{
    /**
     * @var Pegawai
     */
    public $pegawai;
    /**
     * @var int
     */
    public $bulan;
    /**
     * @var PresensiHari[]
     */
    public $presensiHari = false;
    /**
     * @var DateTime
     */
    protected $_date;
    /**
     * @var KetidakhadiranPanjang[]
     */
    protected $_ketidakhadiranPanjang = false;
    /**
     * @var KetidakhadiranJamKerja[]
     */
    private $_ketidakhadiranJamKerja = false;

    public function init()
    {
        if ($this->presensiHari === false) {
            $date = $this->getDate();
            $end = $date->format('t');
            for ($i = 1; $i <= $end; $i++) {
                $dateI = new DateTime($date->format('Y-m-' . sprintf('%02d', $i)));
                $this->presensiHari[$i] = new PresensiHari([
                    'date' => $dateI,
                    'presensiCeklis' => $this,
                ]);

            }
            $this->updatePegawaiRekapAbsensi();
        }
    }

    public function getDate()
    {
        if ($this->_date === null) {
            $this->_date = new DateTime(sprintf('%s-%d-01', User::getTahun(), $this->bulan));
        }
        return $this->_date;
    }

    public function updatePegawaiRekapAbsensi()
    {
        $rekap = $this->pegawai->findOrCreatePegawaiRekapAbsensi($this->bulan, $this->tahun);
        $rekap->jumlah_hari_kerja = $this->getJumlahHariKerja();
        $rekap->jumlah_hadir = $this->getJumlahHadir();
        $rekap->jumlah_tidak_hadir = $this->getJumlahTidakHadir();
        $rekap->jumlah_izin = 0;
        $rekap->jumlah_sakit = 0;
        $rekap->jumlah_cuti = 0;
        $rekap->jumlah_tugas_belajar = 0;
        $rekap->jumlah_dinas_luar = 0;
        $rekap->jumlah_tugas_kedinasan = 0;
        $rekap->jumlah_diklat = 0;
        $rekap->jumlah_alasan_teknis = 0;
        $rekap->jumlah_tidak_hadir_upacara = 0;
        $rekap->jumlah_tidak_hadir_senam = 0;
        $rekap->jumlah_tidak_hadir_apel_pagi = 0;
        $rekap->jumlah_tidak_hadir_apel_sore = 0;
        $rekap->jumlah_tidak_hadir_sidak = 0;
        $rekap->jumlah_tanpa_keterangan = 0;
        $rekap->persen_potongan_fingerprint = $this->getPotongan();
        $rekap->persen_potongan_kegiatan = 0;
        $rekap->persen_potongan_total = 0;
        $rekap->waktu_diperbarui = date('Y-m-d H:i:s');
        $rekap->jumlah_ketidakhadiran_jam_tanpa_keterangan = 0;
        $rekap->save();
        return $rekap;
    }

    public function getJumlahHariKerja()
    {
        return array_sum(
            array_map(
                function (PresensiHari $presensiHari) {

                    if ($presensiHari->isHariLibur()) {
                        return false;
                    }

                    return !empty($presensiHari->presensiJamKerja) && $presensiHari->tanggal <= date('Y-m-d');
                },
                $this->presensiHari
            )
        );
    }

    public function getJumlahHadir()
    {
        return $this->getJumlahHariKerja() - $this->getJumlahTidakHadir();
    }

    public function getJumlahTidakHadir()
    {
        return array_sum(
            array_map(
                function (PresensiHari $presensiHari) {
                    if (empty($presensiHari->presensiJamKerja)) {
                        return false;
                    }

                    foreach ($presensiHari->presensiJamKerja as $presensiJamKerja) {
                        if ($presensiJamKerja->getPotongan() === 0) {
                            return false;
                        }
                    }
                    return true;
                },
                $this->presensiHari
            )
        );
    }

    public function getPotongan()
    {
        return array_sum(
            array_map(
                function (PresensiHari $presensiHari) {
                    return $presensiHari->getPotongan();
                },
                $this->presensiHari
            )
        );
    }

    public function getTahun()
    {
        return User::getTahun();
    }

    /**
     * @param string $tanggal
     * @param int $id_jam_kerja
     *
     * @return false|KetidakhadiranJamKerja
     */
    public function hasKetidakhadiranJamKerja($tanggal, $id_jam_kerja)
    {
        $query = new ArrayQuery(['from' => $this->getAllKetidakhadiranJamKerja()]);
        $result = $query->andWhere(['tanggal' => $tanggal, 'id_jam_kerja' => $id_jam_kerja])
            ->one();
        if ($result) {
            return $result;
        }
        return false;
    }

    /**
     * @return KetidakhadiranJamKerja[]
     */
    public function getAllKetidakhadiranJamKerja()
    {
        if ($this->_ketidakhadiranJamKerja === false) {
            $this->_ketidakhadiranJamKerja = $this->pegawai
                ->getManyKetidakhadiranJamKerja()
                ->andWhere([
                    'between',
                    'tanggal',
                    $this->date->format('Y-m-01'),
                    $this->date->format('Y-m-t')
                ])
                ->all();
        }
        return $this->_ketidakhadiranJamKerja;
    }

    /**
     * @param string $tanggal
     *
     * @return false|KetidakhadiranPanjang
     */
    public function hasKetidakhadiranPanjang($tanggal)
    {
        $query = new ArrayQuery(['from' => $this->getAllKetidakhadiranPanjang()]);
        $result = $query->andWhere(['<=', 'tanggal_mulai', $tanggal])
            ->andWhere(['>=', 'tanggal_selesai', $tanggal])
            ->one();
        if ($result) {
            return $result;
        }
        return false;
    }

    public function getAllKetidakhadiranPanjang()
    {
        if ($this->_ketidakhadiranPanjang === false) {
            $this->_ketidakhadiranPanjang = $this->pegawai
                ->getManyKetidakhadiranPanjang()
                ->andWhere(
                    '
                        (tanggal_mulai <= :tanggal_awal AND (tanggal_selesai >= :tanggal_awal AND tanggal_selesai <= :tanggal_akhir)) OR
                        (tanggal_mulai >= :tanggal_awal AND tanggal_selesai <= :tanggal_akhir) OR
                        ((tanggal_mulai >= :tanggal_awal AND tanggal_mulai <= :tanggal_akhir) AND tanggal_selesai >= :tanggal_akhir) OR
                        (tanggal_mulai <= :tanggal_awal AND tanggal_selesai >= :tanggal_akhir)
                    ',
                    [
                        ':tanggal_awal' => $this->date->format('Y-m-01'),
                        ':tanggal_akhir' => $this->date->format('Y-m-t'),
                    ]
                )
                ->all();
        }
        return $this->_ketidakhadiranPanjang;
    }
}
