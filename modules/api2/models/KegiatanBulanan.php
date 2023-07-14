<?php


namespace app\modules\api2\models;


use app\components\Helper;

class KegiatanBulanan extends \app\modules\kinerja\models\KegiatanBulanan
{
    public static function getQueryByParams($params=[])
    {
        $query = static::find();
        $query->joinWith(['kegiatanTahunan','instansiPegawai']);
        $query->andFilterWhere(['kegiatan_bulanan.id' => @$params['id']]);
        $query->andFilterWhere(['kegiatan_bulanan.id_kegiatan_tahunan' => @$params['id_kegiatan_tahunan']]);
        $query->andFilterWhere(['kegiatan_bulanan.bulan' => @$params['bulan']]);
        $query->andFilterWhere(['kegiatan_bulanan.target' => @$params['target']]);
        $query->andFilterWhere(['kegiatan_bulanan.realisasi' => @$params['realisasi']]);
        $query->andFilterWhere(['kegiatan_bulanan.realisasi_bak' => @$params['realisasi_bak']]);
        return $query;
    }

    public function restJson()
    {
        return [
            'id' => strval($this->id),
            'id_kegiatan_tahunan' => strval($this->id_kegiatan_tahunan),
            'nama_kegiatan_tahunan' => strval(@$this->kegiatanTahunan->nama),
            'nama_pegawai' => strval(@$this->kegiatanTahunan->pegawai->nama),
            'nomor_skp_lengkap' => strval(@$this->getNomorSkpLengkap()),
            'bulan' => strval($this->bulan),
            'nama_bulan' => strval(Helper::getBulanLengkap($this->bulan)),
            'target' => strval($this->target),
            'target_kualitas' => strval($this->target_kualitas),
            'target_waktu' => strval($this->target_waktu),
            'target_biaya' => strval($this->target_biaya),
            'satuan_kuantitas' => strval(@$this->kegiatanTahunan->satuan_kuantitas),
            'satuan_kualitas' => strval(@$this->kegiatanTahunan->satuan_kualitas),
            'satuan_waktu' => strval(@$this->kegiatanTahunan->satuan_waktu),
            'satuan_biaya' => strval(@$this->kegiatanTahunan->satuan_biaya),
            'realisasi' => strval($this->realisasi),
            'realisasi_kualitas' => strval($this->realisasi_kualitas),
            'realisasi_waktu' => strval($this->realisasi_waktu),
            'realisasi_biaya' => strval($this->realisasi_biaya),
            'total_realisasi' => strval(Helper::rp(@$this->kegiatanTahunan->getTotalRealisasi(['bulan'=>$this->bulan]),0)),
            'persen_realisasi' => strval(@$this->getPersenRealisasi()),
            'status' => strval(@$this->kegiatanTahunan->kegiatanStatus->nama),
            'realisasi_bak' => strval($this->realisasi_bak),
        ];
    }

    public static function restApiIndex($params=[])
    {
        $query = static::getQueryByParams($params);

        $query->andWhere(['kegiatan_tahunan.tahun' => @$params['tahun']]);
        $query->andWhere(['kegiatan_tahunan.status_hapus' => 0]);
        $query->andWhere(['kegiatan_tahunan.id_kegiatan_status' => 1]);
        $query->andWhere(['kegiatan_tahunan.id_kegiatan_tahunan_versi' => 2]);

        $query->andWhere('kegiatan_bulanan.target IS NOT NULL');

        if(@$params['mode'] == 'pegawai') {
            $query->andWhere(['kegiatan_tahunan.id_pegawai' => @$params['id_pegawai']]);
        }

        if(@$params['mode'] == 'bawahan') {
            $query->joinWith(['instansiPegawai.jabatan']);
            $query->andWhere(['jabatan.id_induk' => @$params['id_jabatan']]);
        }

        $output = [];

        foreach ($query->all() as $kegiatanBulanan) {
            $output[] = $kegiatanBulanan->restJson();
        }

        return $output;
    }

}
