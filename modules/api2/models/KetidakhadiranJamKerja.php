<?php


namespace app\modules\api2\models;

use app\models\User;
use yii2mod\query\ArrayQuery;
use app\modules\kinerja\models\KegiatanStatus;
use app\modules\absensi\models\KetidakhadiranPanjangJenis;
use app\modules\absensi\models\KetidakhadiranPanjangStatus;
use app\models\DisplayKetidakhadiranInterface;
use app\components\Helper;

class KetidakhadiranJamKerja extends \app\modules\absensi\models\KetidakhadiranJamKerja implements DisplayKetidakhadiranInterface
{
    public static function getQueryByParams($params=[])
    {
        $query = static::find();
        $query->andFilterWhere(['ketidakhadiran_jam_kerja.id' => @$params['id']]);
        $query->andFilterWhere(['ketidakhadiran_jam_kerja.id_pegawai' => @$params['id_pegawai']]);
        $query->andFilterWhere(['ketidakhadiran_jam_kerja.tanggal' => @$params['tanggal']]);
        $query->andFilterWhere(['ketidakhadiran_jam_kerja.id_jam_kerja' => @$params['id_jam_kerja']]);
        $query->andFilterWhere(['ketidakhadiran_jam_kerja.id_ketidakhadiran_jam_kerja_jenis' => @$params['id_ketidakhadiran_jam_kerja_jenis']]);
        $query->andFilterWhere(['ketidakhadiran_jam_kerja.id_ketidakhadiran_jam_kerja_status' => @$params['id_ketidakhadiran_jam_kerja_status']]);

        $query->andWhere(['YEAR(ketidakhadiran_jam_kerja.tanggal)' => @$params['tahun']]);

        return $query;
    }

    public function restJson()
    {
        return [
            'id' => strval($this->id),
            'id_pegawai' => strval($this->id_pegawai),
            'nama_pegawai' => strval($this->pegawai->nama),
            'tanggal' => strval(Helper::getTanggalSingkat($this->tanggal)),
            'id_jam_kerja' => strval($this->id_jam_kerja),
            'nama_jam_kerja' => strval($this->jamKerja->nama),
            'id_ketidakhadiran_jam_kerja_jenis' => strval($this->id_ketidakhadiran_jam_kerja_jenis),
            'jenis_ketidakhadiran' => strval(@$this->ketidakhadiranJamKerjaJenis->nama),
            'id_ketidakhadiran_jam_kerja_status' => strval($this->id_ketidakhadiran_jam_kerja_status),
            'status_ketidakhadiran' => strval(@$this->ketidakhadiranJamKerjaStatus->nama),
            'berkas' => strval($this->berkas),
            'keterangan' => strval($this->keterangan),
            'keterangan_status' => strval($this->keterangan_status),
        ];
    }

    public static function restApiIndex($params=[])
    {
        $query = static::getQueryByParams($params);

        $output = [];
        /* @var $ketidahadiranJamKerja KetidakhadiranJamKerja */
        foreach ($query->all() as $ketidahadiranJamKerja) {
            $output[] = $ketidahadiranJamKerja->restJson();
        }

        return $output;
    }

    public static function restApiIndexBawahan($params=[])
    {
        $query = static::getQueryByParams($params);

        if(@$params['namaPegawai'] != null) {
            $query->joinWith(['pegawai']);
            $query->andFilterWhere(['like','pegawai.nama', @$params['namaPegawai']]);
        }

        $query->joinWith(['pegawai.allInstansiPegawaiAktif.jabatan']);
        $query->andWhere(['jabatan.id_induk' => @$params['id_jabatan']]);
        $query->andWhere(['ketidakhadiran_jam_kerja.id_ketidakhadiran_jam_kerja_status' => 2]);

        $output = [];
        /* @var $ketidahadiranJamKerja \app\modules\kinerja\models\KetidakhadiranJamKerja */
        foreach ($query->all() as $ketidahadiranJamKerja) {
            $output[] = $ketidahadiranJamKerja->restJson();
        }

        return $output;
    }

    public static function restApiView($id)
    {
        $query = static::getQueryByParams(['id' => $id]);
    }
}
