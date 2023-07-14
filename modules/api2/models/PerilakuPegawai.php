<?php


namespace app\modules\api2\models;

use app\models\User;
use yii2mod\query\ArrayQuery;
use app\components\Helper;
use app\models\InstansiPegawai;
use app\models\JabatanJenis;
use app\models\PerilakuJenis;

class PerilakuPegawai extends \app\modules\kinerja\models\PerilakuPegawai
{
    public static function getQueryByParams($params=[])
    {
        $query = static::find();
        $query->andFilterWhere(['perilaku_pegawai.id' => @$params['id']]);
        $query->andFilterWhere(['perilaku_pegawai.id_pegawai' => @$params['id_pegawai']]);
        $query->andFilterWhere(['perilaku_pegawai.bulan' => @$params['bulan']]);
        $query->andFilterWhere(['perilaku_pegawai.tahun' => @$params['tahun']]);
        $query->andFilterWhere(['perilaku_pegawai.id_perilaku_jenis' => @$params['id_perilaku_jenis']]);
        $query->andFilterWhere(['perilaku_pegawai.nilai' => @$params['nilai']]);

        return $query;
    }

    public function restJson()
    {
        return [
            'id' => strval($this->id),
            'id_pegawai' => strval($this->id_pegawai),
            'nama_pegawai' => strval(@$this->pegawai->nama),
            'nip' => strval(@$this->pegawai->nip),
            'nama_jabatan' => strval(@$this->pegawai->getNamaJabatan()),
            'jenis_jabatan' => strval(@$this->pegawai->getJabatanJenis()),
            'bulan' => strval($this->bulan),
            'nama_bulan' => strval(Helper::getBulanLengkap($this->bulan)),
            'tahun' => strval($this->tahun),
            'nilai_orientasi_pelayanan' => strval($this->getNilai(1)),
            'nilai_integritas' => strval($this->getNilai(2)),
            'nilai_komitmen' => strval($this->getNilai(3)),
            'nilai_disiplin' => strval($this->getNilai(4)),
            'nilai_kerjasama' => strval($this->getNilai(5)),
            'nilai_kepemimpinan' => strval($this->getNilai(6)),
            'jumlah_nilai' => strval($this->getJumlahNilai()),
            'nilai_rata_rata' => strval($this->getNilaiRataRata()),
        ];
    }

    public static function restApiIndex($params=[])
    {
        $query = static::getQueryByParams($params);

        $output = [];
        /* @var $perilakuPegawai PerilakuPegawai */
        foreach ($query->all() as $perilakuPegawai) {
            $output[] = $perilakuPegawai->restJson();
        }

        return $output;
    }

    public static function restApiView($id)
    {
        $query = static::getQueryByParams(['id' => $id]);
    }

    public function getNilai($id)
    {
        $query = static::find()
            ->andWhere(['perilaku_pegawai.id_pegawai' => $this->id_pegawai])
            ->andWhere(['perilaku_pegawai.id_perilaku_jenis' => $id])
            ->andWhere(['perilaku_pegawai.bulan' => $this->bulan])
            ->andWhere(['perilaku_pegawai.tahun' => $this->tahun])
            ->one();

        return $query->nilai;
    }

    public function getJumlahNilai()
    {
        $query = static::find();
        $query->andWhere(['perilaku_pegawai.id_pegawai' => $this->id_pegawai]);
        $query->andWhere(['perilaku_pegawai.bulan' => $this->bulan]);
        $query->andWhere(['perilaku_pegawai.tahun' => $this->tahun]);

        if(@$this->pegawai->getIdJabatanJenis() != JabatanJenis::STRUKTURAL) {
            $query->andWhere('perilaku_pegawai.id_perilaku_jenis != :id_perilaku_jenis', [
                ':id_perilaku_jenis' => PerilakuJenis::KEPEMIMPINAN
            ]);
        }

        return $query->sum('nilai');
    }

    public function getNilaiRataRata()
    {
        $query = static::find();
        $query->andWhere(['perilaku_pegawai.id_pegawai' => $this->id_pegawai]);
        $query->andWhere(['perilaku_pegawai.bulan' => $this->bulan]);
        $query->andWhere(['perilaku_pegawai.tahun' => $this->tahun]);

        if(@$this->pegawai->getIdJabatanJenis() != JabatanJenis::STRUKTURAL) {
            $query->andWhere('perilaku_pegawai.id_perilaku_jenis != :id_perilaku_jenis', [
                ':id_perilaku_jenis' => PerilakuJenis::KEPEMIMPINAN
            ]);
        }

        $jumlah = $query->count();

        if($jumlah == 0){
            return 0;
        }

        $totalNilai = 0;

        foreach ($query->all() as $perilakuPegawai) {
            $totalNilai += $perilakuPegawai->nilai;
        }

        $rataRata = $totalNilai/$jumlah;

        return substr($rataRata, 0,5);
    }
}
