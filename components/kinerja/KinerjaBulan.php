<?php

namespace app\components\kinerja;

use app\models\Pegawai;
use app\modules\absensi\models\KetidakhadiranPanjang;
use app\modules\absensi\models\KetidakhadiranPanjangJenis;
use app\modules\absensi\models\KetidakhadiranPanjangStatus;
use app\modules\absensi\models\PegawaiDispensasi;
use app\modules\kinerja\models\KegiatanHarian;
use app\modules\kinerja\models\KegiatanHarianDiskresi;
use DateTime;
use yii\base\InvalidConfigException;

class KinerjaBulan extends BaseKinerja
{
    /**
     * @var DateTime
     */
    protected $_date;

    /**
     * @var Pegawai
     */
    public $pegawai;

    public $tahun;

    public $bulan;

    private $_arrayKinerjaHari;

    private $_arrayKetidakhadiranPanjang;

    private $_totalPersenPotongan;

    private $_arrayKegiatanHarianDiskresi;

    private $_isDispensasiCkhpSatuBulan;

    private $_arrayKegiatanHarian;

    /**
     * @throws InvalidConfigException
     */
    public function execute()
    {
        if ($this->tahun === null) {
            throw new InvalidConfigException('The "tahun" property must be set.');
        }

        if (in_array($this->bulan, range(1, 12)) != true) {
            throw new InvalidConfigException('Bulan harus diantara 1 - 12');
        }

        if ($this->pegawai === null) {
            throw new InvalidConfigException('The "pegawai" property must be set.');
        }

        $this->getArrayKinerjaHari();
        $this->getArrayKegiatanHarian();
        $this->getArrayKetidakhadiranPanjang();
        $this->getArrayKegiatanHarianDiskresi();
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        if($this->_date !== null) {
            return $this->_date;
        }

        $this->_date = DateTime::createFromFormat('Y-n-d', $this->tahun.'-'.$this->bulan.'-01');

        return $this->_date;
    }

    /**
     * @return KinerjaHari[]
     */
    public function getArrayKinerjaHari(): array
    {
        if($this->_arrayKinerjaHari === null) {
            $this->setArrayKinerjaHari();
        }

        return $this->_arrayKinerjaHari;
    }

    public function setArrayKinerjaHari()
    {
        $date = $this->getDate();
        $end = $date->format('t');

        $start = 1;

        for ($i = $start; $i <= $end; $i++) {

            $hari = sprintf('%02d', $i);
            $tanggal =  $date->format('Y-m').'-'.$hari;

            $kinerjaHari = new KinerjaHari([
                'tanggal' => $tanggal,
                'kinerjaBulan' => $this,
            ]);

            $kinerjaHari->execute();
            $this->_arrayKinerjaHari["$i"] = $kinerjaHari;
        }
        unset($dateI, $date, $end);
    }

    public function getArrayKegiatanHarian()
    {
        if ($this->_arrayKegiatanHarian !== null) {
            return $this->_arrayKegiatanHarian;
        }

        $date = $this->getDate();

        $query = KegiatanHarian::find();
        $query->andWhere([
            'id_pegawai' => $this->pegawai->id,
            'id_kegiatan_harian_versi' => [2, 3],
        ]);
        $query->andWhere('tanggal >= :tanggal_awal AND tanggal <= :tanggal_akhir', [
            ':tanggal_awal' => $date->format('Y-m-01'),
            ':tanggal_akhir' => $date->format('Y-m-t'),
        ]);

        $this->_arrayKegiatanHarian = $query->all();

        return $this->_arrayKegiatanHarian;
    }

    /**
     * @return array|\yii\db\ActiveRecord[]|KetidakhadiranPanjang[]
     */
    public function getArrayKetidakhadiranPanjang()
    {
        if ($this->_arrayKetidakhadiranPanjang !== null) {
            return $this->_arrayKetidakhadiranPanjang;
        }

        $date = $this->getDate();

        $query = KetidakhadiranPanjang::find();
        $query->andWhere([
            'id_pegawai' => $this->pegawai->id,
            'id_ketidakhadiran_panjang_status' => KetidakhadiranPanjangStatus::SETUJU
        ]);
        $query->andWhere('tanggal_mulai <= :akhir_bulan AND tanggal_selesai >= :awal_bulan',[
            ':awal_bulan' => $date->format('Y-m-01'),
            ':akhir_bulan' => $date->format('Y-m-t')
        ]);
        $query->andWhere(['NOT IN', 'id_ketidakhadiran_panjang_jenis', [
            KetidakhadiranPanjangJenis::KETIDAKHADIRAN_TUGAS_KEDINASAN,
            KetidakhadiranPanjangJenis::KETIDAKHADIRAN_ALASAN_TEKNIS,
            KetidakhadiranPanjangJenis::KETIDAKHADIRAN_ALASAN_KHUSUS,
        ]]);

        $this->_arrayKetidakhadiranPanjang = $query->all();

        return $this->_arrayKetidakhadiranPanjang;
    }

    public function getArrayKegiatanHarianDiskresi()
    {
        if ($this->_arrayKegiatanHarianDiskresi !== null) {
            return $this->_arrayKegiatanHarianDiskresi;
        }

        $date = $this->getDate();

        $query = KegiatanHarianDiskresi::find();
        $query->andWhere(['id_pegawai' => $this->pegawai->id]);
        $query->andWhere('tanggal >= :tanggal_awal AND tanggal <= :tanggal_akhir', [
            ':tanggal_awal' => $date->format('Y-m-01'),
            ':tanggal_akhir' => $date->format('Y-m-t'),
        ]);

        $this->_arrayKegiatanHarianDiskresi = $query->all();

        return $this->_arrayKegiatanHarianDiskresi;
    }

    public function getTotalPersenPotongan()
    {
        if ($this->_totalPersenPotongan !== null) {
            return $this->_totalPersenPotongan;
        }

        $totalPersenPotongan = 0;
        foreach ($this->getArrayKinerjaHari() as $kinerjaHari) {
            $totalPersenPotongan += $kinerjaHari->getPersenPotongan();
        }

        $this->_totalPersenPotongan = $totalPersenPotongan;

        return $this->_totalPersenPotongan;
    }

    public function isDispensasiCkhpSatubulan()
    {
        if ($this->_isDispensasiCkhpSatuBulan !== null) {
            return $this->_isDispensasiCkhpSatuBulan;
        }

        $date = $this->getDate();

        $query = PegawaiDispensasi::find();
        $query->andWhere(['id_pegawai' => $this->pegawai->id]);
        $query->andWhere('tanggal_mulai <= :tanggal_mulai AND tanggal_selesai >= :tanggal_selesai',[
            ':tanggal_mulai' => $date->format('Y-m-01'),
            ':tanggal_selesai' => $date->format('Y-m-t'),
        ]);

        $this->_isDispensasiCkhpSatuBulan = false;

        if ($query->count() > 0) {
            $this->_isDispensasiCkhpSatuBulan = true;
        }

        return $this->_isDispensasiCkhpSatuBulan;
    }

}
