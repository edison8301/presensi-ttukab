<?php


namespace app\modules\api2\models;


use app\components\Helper;
use app\modules\kinerja\models\KegiatanHarianTambahan;
use app\modules\kinerja\models\KegiatanStatus;

class KegiatanHarian extends \app\modules\kinerja\models\KegiatanHarian
{
    public static function getQueryByParams($params=[])
    {
        $query = static::find();
        $query->joinWith(['instansiPegawai']);
        $query->with(['kegiatanHarianTambahan', 'kegiatanTahunan', 'kegiatanHarianJenis',
                'kegiatanStatus', 'pegawai']);
        $query->andFilterWhere(['kegiatan_harian.id' => @$params['id']]);
        $query->andFilterWhere(['kegiatan_harian.id_pegawai' => @$params['id_pegawai']]);
        $query->andFilterWhere(['kegiatan_harian.id_kegiatan_status' => @$params['id_kegiatan_status']]);
        if (@$params['tanggal']) {
            $query->filterByTanggal($params['tanggal']);
        }
        if (@$params['bulan'] AND @$params['tahun']) {
            $query->filterByBulan($params['bulan'], $params['tahun']);
        }
        if (@$params['tahun']) {
            $query->filterByTahun($params['tahun']);
        }
        // $query->orderBy(['kegiatan_harian.tanggal' => SORT_ASC, 'kegiatan_harian.jam_mulai' => SORT_ASC]);
        $query->orderBy(['kegiatan_harian.id' => SORT_DESC]);

        return $query;
    }

    public function restJson()
    {
        return [
            'id' => strval($this->id),
            'id_pegawai' => strval($this->id_pegawai),
            'nomor_skp' => strval(@$this->getNomorSkp()),
            'nomor_skp_lengkap' => strval(@$this->getNomorSkpLengkap()),
            'id_kegiatan_tahunan' => strval($this->id_kegiatan_tahunan),
            'nama_kegiatan_tahunan' => strval(@$this->kegiatanTahunan->nama),
            'hari' => strval(Helper::getHari($this->tanggal)),
            'tanggal' => strval($this->tanggal),
            'tanggal_format' => strval(Helper::getTanggalSingkat($this->tanggal)),
            'uraian' => strval($this->uraian),
            'uraian_lainya' => strval($this->getUraianLainya()),
            'id_kegiatan_harian_tambahan' => strval($this->id_kegiatan_harian_tambahan),
            'nama_kegiatan_harian_tambahan' => strval(@$this->kegiatanHarianTambahan->nama),
            'id_kegiatan_harian_jenis' => strval($this->id_kegiatan_harian_jenis),
            'nama_kegiatan_harian_jenis' => strval($this->getNamaKegiatanHarianJenis()),
            'jam_mulai' => strval($this->getJamMulai()),
            'jam_selesai' => strval($this->getJamSelesai()),
            'kuantitas' => strval($this->kuantitas),
            'satuan' => strval($this->satuan),
            'id_kegiatan_status' => strval($this->id_kegiatan_status),
            'nama_kegiatan_status' => strval(@$this->kegiatanStatus->nama),
            'nama_pegawai' => strval(@$this->pegawai->nama),
            'satuan_kegiatan_tahunan' => strval(@$this->kegiatanTahunan->satuan_kuantitas),
            'realisasi_kuantitas' => strval($this->realisasi_kuantitas),
            'realisasi_kualitas' => strval($this->realisasi_kualitas),
            'realisasi_waktu' => strval($this->realisasi_waktu),
            'realisasi_biaya' => strval($this->realisasi_biaya),
        ];
    }

    public function getUraianLainya()
    {
        $output = '';
        if($this->id_kegiatan_harian_jenis== KegiatanHarian::UTAMA) {
            $output .= @$this->getNamaKegiatanTahunan();
        }

        if($this->id_kegiatan_harian_jenis== KegiatanHarian::TAMBAHAN) {
            $output .= @$this->kegiatanHarianTambahan->nama;
        }

        return $output;
    }

    public static function restApiIndex($params=[])
    {
        $query = static::getQueryByParams($params);

        if(@$params['limit'] != null) {
            $query->limit(@$params['limit']);
        }

        $output = [];
        /* @var $kegiatanHarian \app\modules\kinerja\models\KegiatanHarian */
        foreach ($query->all() as $kegiatanHarian) {
            $output[] = $kegiatanHarian->restJson();
        }

        return $output;
    }

    public static function restApiIndexBawahan($params=[])
    {
        $query = static::getQueryByParams($params);

        $query->joinWith(['instansiPegawai.jabatan']);
        $query->andWhere(['jabatan.id_induk' => @$params['id_jabatan']]);
        $query->andWhere('kegiatan_harian.id_kegiatan_status != :konsep',[
            ':konsep' => kegiatanStatus::KONSEP
        ]);

        $output = [];
        /* @var $kegiatanHarian \app\modules\kinerja\models\KegiatanHarian */
        foreach ($query->all() as $kegiatanHarian) {
            $output[] = $kegiatanHarian->restJson();
        }

        return $output;
    }

    public static function restApiIndexByKegiatanTahunan($params=[])
    {
        $query = static::getQueryByParams($params);
        $query->andFilterWhere(['kegiatan_harian.id_kegiatan_tahunan' => @$params['id_kegiatan_tahunan']]);
        $query->andWhere(['kegiatan_harian.status_hapus' => 0]);
        $query->andWhere(['between','tanggal',
                date('Y-m-01', strtotime(@$params['tahun'] . '-' . sprintf('%02d', @$params['bulan']) . '-01')),
                date('Y-m-t', strtotime(@$params['tahun'] . '-' . sprintf('%02d', @$params['bulan']) . '-01'))
            ]);

        $output = [];
        /* @var $kegiatanHarian \app\modules\kinerja\models\KegiatanHarian */
        foreach ($query->all() as $kegiatanHarian) {
            $output[] = $kegiatanHarian->restJson();
        }

        return $output;
    }

    public function setIdKegiatanStatus($id_kegiatan_status)
    {
        $this->id_kegiatan_status = $id_kegiatan_status;
    }

    public function getNamaKegiatanHarianJenis()
    {
        if ($this->id_kegiatan_harian_jenis == 1) {
            return 'Utama';
        }

        if ($this->id_kegiatan_harian_jenis == 2) {
            return 'Tambahan';
        }

        return '';
    }

    public function getJamMulai()
    {
        $datetime = \DateTime::createFromFormat('H:i:s', $this->jam_mulai);

        if ($datetime == false) {
            return null;
        }

        return $datetime->format('H:i');
    }

    public function getJamSelesai()
    {
        $datetime = \DateTime::createFromFormat('H:i:s', $this->jam_selesai);

        if ($datetime == false) {
            return null;
        }

        return $datetime->format('H:i');
    }

}
