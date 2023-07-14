<?php

namespace app\modules\api2\models;

use Imagine\Image\Box;
use Yii;
use app\components\Helper;
use app\models\Artikel as BaseArtikel;
use app\models\JadwalKehadiran;
use app\models\Pegawai;
use app\modules\iclock\models\Userinfo;
use yii\helpers\Url;
use yii\imagine\Image;
class Artikel extends \app\models\Artikel
{

    public static function getQueryByParams($params=[])
    {
        $query = static::find();
        $query->andFilterWhere(['id' => @$params['id']]);
        $query->andFilterWhere(['judul' => @$params['judul']]);
        $query->orderBy(['waktu_terbit' => SORT_DESC]);

        return $query;
    }

        public function restJson()
    {
        return [
            'id' => strval($this->id),
            'judul' => strval($this->getJudulArtikelUcWords()),
            'slug' => strval($this->slug),
            'konten' => strval($this->konten),
            'id_user_buat' => strval($this->id_user_buat),            
            'id_user_ubah' => strval($this->id_user_ubah),
            'id_artikel_kategori' => strval($this->id_artikel_kategori),
            'waktu_buat' => strval($this->waktu_buat),
            'waktu_ubah' => strval($this->waktu_ubah),
            'waktu_terbit' => strval($this->waktu_terbit),
            'thumbnail' => strval($this->thumbnail),
            'jumlah_dilihat' => strval($this->jumlah_dilihat),
            'thumbnail_mobile' => strval($this->isThumbnailIsExist() == null ? '/images/image-not-found.png' : '/uploads/artikel/'.$this->thumbnail),
            'waktu_terbit_format' => strval($this->getWaktuTerbitFormat()),
            'url_gambar' => strval($this->getUrlGambar()),
        ];
    }
    
    public function fields()
    {
        $fields = parent::fields();

        $fields['isman'] = function (){
            return 'isman';
        };

         $fields['thumbnail-mobile'] = function () {
            if ($this->isThumbnailIsExist()) {
                return '/uploads/artikel/'.$this->thumbnail;
            }

            return '/uploads/image-not-found.png';
        };

        $fields['waktu_terbit_format'] = function () {
            if ($this->waktu_terbit != null) {
            $datetime = \DateTime::createFromFormat('Y-m-d H:i:s', $this->waktu_terbit);
            return Helper::getWaktuWIB($datetime->format('Y-m-d H:i:s'));
            } else {
                return 'waktu terbit belum di isi';
            }
        };

        return $fields;
    }

    public static function restApiIndex($params=[])
    {
        $query = static::getQueryByParams($params);

        if(@$params['limit'] != null) {
            $query->limit(@$params['limit']);
        }

        $output = [];
        /* @var $artikel \app\modules\Artikel */
        foreach ($query->all() as $artikel) {
            $output[] = $artikel->restJson();
        }

        return $output;
    }

    public function getWaktuTerbitFormat()
    {
        if ($this->waktu_terbit != null) {
            $datetime = \DateTime::createFromFormat('Y-m-d H:i:s', $this->waktu_terbit);
            return Helper::getWaktuWIB($datetime->format('Y-m-d H:i:s'));
        } else {
            return 'waktu terbit belum di isi';
        }
    }

    public function isThumbnailIsExist()
    {
        $path = \Yii::getAlias('@app').'/web/uploads/artikel/';

        if ($this->thumbnail !== null AND $this->thumbnail !== '' AND is_file($path.$this->thumbnail)) {
            return true;
        }

        return false;
    }

    public  function getUrlGambar()
    {
        $path = Yii::getAlias('@app').'/web/uploads/artikel/';

        if ($this->thumbnail !== null AND file_exists($path.$this->thumbnail)) {
            return Url::base(true).'/uploads/artikel/'.$this->thumbnail;
        }

        return Url::base(true).'/images/image-not-found.png';
    }
}
