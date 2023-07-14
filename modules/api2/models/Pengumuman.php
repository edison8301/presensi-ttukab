<?php

namespace app\modules\api2\models;

use app\components\Helper;

class Pengumuman extends \app\models\Pengumuman
{   
    protected $nip;
    
    protected static $_template = [
        'pegawai' => 'getNamaPegawai',
        'pegawai/instansi' => 'getNamaPegawai',
    ];

    public static function getQueryByParams($params=[])
    {
        $query = static::find();
        $query->andFilterWhere(['id' => @$params['id']]);
        $query->andFilterWhere(['judul' => @$params['judul']]);
        $query->andFilterWhere(['teks' => @$params['teks']]);
        $query->andFilterWhere(['tanggal_mulai' => @$params['tanggal_mulai']]);
        $query->andFilterWhere(['tanggal_selesai' => @$params['tanggal_selesai']]);
        $query->orderBy(['waktu_dibuat' => SORT_DESC]);

        return $query;
    }

    public function restJson()
    {
        return [
            'id' => strval($this->id),
            'judul' => strval($this->judul),
            'teks' => strval($this->getText()),
            'tanggal_mulai' => strval(Helper::getTanggal($this->tanggal_mulai)),
            'tanggal_selesai' => strval(Helper::getTanggal($this->tanggal_selesai)),
        ];
    }

    public static function restApiIndex($params=[])
    {
        $query = static::getQueryByParams($params);

        if(@$params['limit'] != null) {
            $query->limit(@$params['limit']);
        }

        $query->andWhere(['not like', 'teks' , '{instansi}']);

        $output = [];
        /* @var $pengumuman \app\modules\Pengumuman */
        foreach ($query->all() as $pengumuman) {
            $pengumuman->nip = @$params['nip'];
            $output[] = $pengumuman->restJson();
        }

        return $output;
    }
    
    protected function getText()
    {
        $text = $this->teks;
        foreach (static::$_template as $key => $value) {
            $text = str_ireplace('{' . $key . '}', $this->getNamaPegawai(), $text);
        }
        return $text;
    }

    public function getNamaPegawai()
    {
        $pegawai = Pegawai::findOne(['nip' => $this->nip]);
        if ($pegawai == null) {
            return null;
        }
        return $pegawai->nama;
    }
}