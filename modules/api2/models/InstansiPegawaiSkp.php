<?php


namespace app\modules\api2\models;


use app\components\Helper;
use app\modules\kinerja\models\KegiatanHarianTambahan;

class InstansiPegawaiSkp extends \app\modules\kinerja\models\InstansiPegawaiSkp
{
    public static function getQueryByParams($params=[])
    {
        $query = static::find();
        $query->joinWith(['instansiPegawai']);
        $query->andFilterWhere([
            'instansi_pegawai_skp.tahun' => @$params['tahun'],
            'instansi_pegawai.id_pegawai' => @$params['id_pegawai']
        ]);

        return $query;
    }

    public function restJson()
    {
        return [
            'id' => strval($this->id),
            'id_instansi_pegawai' => strval($this->id_instansi_pegawai),
            'nomor_skp' => $this->nomor,
            'nama_nomor_skp' => $this->nomor.' : '.$this->instansiPegawai->namaJabatan.' - '.@$this->instansi->nama,
        ];
    }

    public static function restListJabatan($params=[])
    {
        $query = static::getQueryByParams($params);

        $output = [];
        foreach ($query->all() as $instansiPegawaiSkp) {
            $output[] = $instansiPegawaiSkp->restJson();
        }

        return $output;
    }

}
