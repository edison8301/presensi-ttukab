<?php


namespace app\modules\api2\models;

use app\models\User;
use yii2mod\query\ArrayQuery;
use app\modules\kinerja\models\KegiatanStatus;
use DateTime;

class KegiatanTahunan extends \app\modules\kinerja\models\KegiatanTahunan
{
    public static function getQueryByParams($params=[])
    {
        $query = static::find();
        $query->andFilterWhere(['kegiatan_tahunan.id' => @$params['id']]);
        $query->andFilterWhere(['kegiatan_tahunan.id_pegawai' => @$params['id_pegawai']]);
        $query->andFilterWhere(['kegiatan_tahunan.tahun' => @$params['tahun']]);
        $query->andFilterWhere(['kegiatan_tahunan.id_kegiatan_status' => @$params['id_kegiatan_status']]);
        $query->andFilterWhere(['kegiatan_tahunan.id_kegiatan_tahunan_jenis' => @$params['id_kegiatan_tahunan_jenis']]);
        $query->andFilterWhere(['kegiatan_tahunan.id_kegiatan_tahunan_versi' => @$params['id_kegiatan_tahunan_versi']]);

        return $query;
    }

    public function restJson($params=[])
    {
        $bulan = @$params['bulan'];

        if ($bulan == null) {
            $bulan = date('n');
        }

        $kegiatanBulanan = $this->findKegiatanBulananByBulan($bulan);

        return [
            'id' => strval($this->id),
            'tahun' => strval($this->tahun),
            'id_pegawai' => strval($this->id_pegawai),
            'id_instansi_pegawai' => strval($this->id_instansi_pegawai),
            'nama' => strval($this->nama),
            'nomor_skp' => strval(@$this->instansiPegawaiSkp->nomor),
            'nama_nomor_skp' => strval(@$this->instansiPegawaiSkp->nomor.' : '.@$this->instansiPegawai->namaJabatan.' - '.@$this->instansiPegawaiSkp->instansi->nama),
            'nama_kegiatan_tahunan_atasan' => strval(@$this->kegiatanTahunanAtasan->nama),
            'id_induk' => strval($this->id_induk),
            'indikator_kuantitas' => strval($this->indikator_kuantitas),
            'indikator_kualitas' => strval($this->indikator_kualitas),
            'indikator_waktu' => strval($this->indikator_waktu),
            'indikator_biaya' => strval($this->indikator_biaya),
            'satuan_kuantitas' => strval($this->satuan_kuantitas),
            'satuan_kualitas' => strval($this->satuan_kualitas),
            'satuan_waktu' => strval($this->satuan_waktu),
            'satuan_biaya' => strval($this->satuan_biaya),
            'target_kuantitas' => strval($this->target_kuantitas),
            'target_kualitas' => strval($this->target_kualitas),
            'target_waktu' => strval($this->target_waktu),
            'target_biaya' => strval($this->target_biaya),
            'target_angka_kredit' => strval($this->target_angka_kredit),
            'realisasi_kuantitas' => strval($this->realisasi_kuantitas),
            'realisasi_kualitas' => strval($this->realisasi_kualitas),
            'realisasi_waktu' => strval($this->realisasi_waktu),
            'realisasi_angka_kredit' => strval($this->realisasi_angka_kredit),
            'realisasi_biaya' => strval($this->realisasi_biaya),
            'id_pegawai_penyetuju' => strval($this->id_pegawai_penyetuju),
            'id_kegiatan_status' => strval($this->id_kegiatan_status),
            'nama_kegiatan_status' => strval($this->kegiatanStatus->nama),
            'jumlah_kegiatan_bulanan' => strval($this->kegiatanBulananCount),
            'jumlah_tahapan' => strval(count($this->manySub)),
            'nama_pegawai' => strval(@$this->pegawai->nama),
            'status_kegiatan_tahunan' => 'status_kegiatan',
            'id_kegiatan_tahunan_atasan' => strval($this->id_kegiatan_tahunan_atasan),
            'id_kegiatan_tahunan_jenis' => strval($this->id_kegiatan_tahunan_jenis),
            'id_kegiatan_tahunan_versi' => strval($this->id_kegiatan_tahunan_versi),
            'target_kuantitas_bulan' => strval(@$kegiatanBulanan->target),
            'target_kualitas_bulan' => strval(@$kegiatanBulanan->target_kualitas),
            'target_biaya_bulan' => strval(@$kegiatanBulanan->target_biaya),
            'target_waktu_bulan' => strval(@$kegiatanBulanan->target_waktu),
        ];
    }

    public static function restApiIndex($params=[])
    {
        $query = static::getQueryByParams($params);

        $output = [];
        /* @var $kegiatanTahunan KegiatanTahunan */
        foreach ($query->all() as $kegiatanTahunan) {
            $output[] = $kegiatanTahunan->restJson();
        }

        return $output;
    }

    public static function restApiIndexBawahan($params=[])
    {
        $query = static::getQueryByParams($params);

        $query->joinWith(['instansiPegawai.jabatan']);
        $query->andWhere(['jabatan.id_induk' => @$params['id_jabatan']]);
        $query->andWhere('kegiatan_tahunan.id_kegiatan_status != :konsep',[
            ':konsep' => kegiatanStatus::KONSEP
        ]);

        $output = [];
        /* @var $kegiatanHarian \app\modules\kinerja\models\KegiatanHarian */
        foreach ($query->all() as $kegiatanHarian) {
            $output[] = $kegiatanHarian->restJson();
        }

        return $output;
    }

    public static function restApiListAtasan($params=[])
    {
        $model = new KegiatanTahunan();
        $model->tahun = date('Y');
        $model->id_pegawai = @$params['id_pegawai'];
        $model->id_instansi_pegawai = @$params['id_instansi_pegawai'];

        $output = [];

        foreach ($model->getListKegiatanTahunanAtasan(['id_kegiatan_tahunan_versi' => 2]) as $key => $value) {
            $output[] = [
                'id' => strval($key),
                'nama' => strval($value),
            ];
        }

        return $output;
    }

    public static function restApiIndexBulanIni($params=[])
    {
        $tahun = @$params['tahun'];
        $bulan = @$params['bulan'];

        if ($tahun == null) {
            $tahun = date('Y');   
        }

        if ($bulan == null) {
            $bulan = date('m');
        }

        $query = KegiatanTahunan::find();
        $query->joinWith(['manyKegiatanBulanan']);
        $query->andWhere([
            'kegiatan_tahunan.id_pegawai' => @$params['id_pegawai'],
            'kegiatan_tahunan.id_kegiatan_status' => KegiatanStatus::SETUJU,
            'kegiatan_tahunan.id_kegiatan_tahunan_jenis' => @$params['id_kegiatan_tahunan_jenis'],
            'kegiatan_tahunan.id_kegiatan_tahunan_versi' => 2,
            'kegiatan_bulanan.bulan' => $bulan,
        ]);
        $query->andWhere('kegiatan_bulanan.target IS NOT NULL');
        $query->aktif();
        
        $allKegiatanTahunan = $query->all();

        $output = [];

        foreach ($allKegiatanTahunan as $kegiatanTahunan) {
            $output[] = $kegiatanTahunan->restJson();
        }

        return $output;
    }

    public static function restApiView($id)
    {
        $query = static::getQueryByParams(['id' => $id]);
    }

    public function setIdKegiatanStatus($id_kegiatan_status)
    {
        $this->id_kegiatan_status = $id_kegiatan_status;
    }
}
