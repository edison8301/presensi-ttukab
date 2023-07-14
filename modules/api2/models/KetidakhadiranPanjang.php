<?php


namespace app\modules\api2\models;

use app\models\User;
use yii2mod\query\ArrayQuery;
use app\modules\kinerja\models\KegiatanStatus;
use app\modules\absensi\models\KetidakhadiranPanjangJenis;
use app\modules\absensi\models\KetidakhadiranPanjangStatus;
use app\models\DisplayKetidakhadiranInterface;

class KetidakhadiranPanjang extends \app\modules\absensi\models\KetidakhadiranPanjang implements DisplayKetidakhadiranInterface
{
    public static function getQueryByParams($params=[])
    {
        $query = static::find();
        $query->andFilterWhere(['ketidakhadiran_panjang.id' => @$params['id']]);
        $query->andFilterWhere(['ketidakhadiran_panjang.id_pegawai' => @$params['id_pegawai']]);
        $query->andFilterWhere(['ketidakhadiran_panjang.id_ketidakhadiran_panjang_jenis' => @$params['id_ketidakhadiran_panjang_status']]);
        $query->andFilterWhere(['ketidakhadiran_panjang.keterangan' => @$params['keterangan']]);
        $query->andFilterWhere(['ketidakhadiran_panjang.tanggal_mulai' => @$params['tanggal_mulai']]);
        $query->andFilterWhere(['ketidakhadiran_panjang.tanggal_selesai' => @$params['tanggal_selesai']]);
        $query->andFilterWhere(['ketidakhadiran_panjang.id_ketidakhadiran_panjang_status' => @$params['id_ketidakhadiran_panjang_status']]);        

        $query->andWhere(['YEAR(ketidakhadiran_panjang.tanggal_mulai)' => @$params['tahun']]);
        $query->andWhere(['YEAR(ketidakhadiran_panjang.tanggal_selesai)' => @$params['tahun']]);

        return $query;
    }

    public function restJson()
    {
        return [
            'id' => strval($this->id),
        ];
    }

    public static function restApiIndex($params=[])
    {
        $query = static::getQueryByParams($params);

        $output = [];
        /* @var $ketidakhadiranPanjang KetidakhadiranPanjang */
        foreach ($query->all() as $ketidakhadiranPanjang) {
            $output[] = $ketidakhadiranPanjang->restJson();
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
        
        $query->joinWith(['allInstansiPegawaiAktif.jabatan']);
        $query->andWhere(['jabatan.id_induk' => @$params['id_jabatan']]);
        $query->andWhere(['ketidakhadiran_panjang.id_ketidakhadiran_panjang_status' => 2]);                        

        $output = [];
        /* @var $ketidakhadiranPanjang \app\modules\kinerja\models\KetidakhadiranPanjang */
        foreach ($query->all() as $ketidakhadiranPanjang) {
            $output[] = $ketidakhadiranPanjang->restJson();
        }

        return $output;
    }

    public static function restApiView($id)
    {
        $query = static::getQueryByParams(['id' => $id]);
    }
}
