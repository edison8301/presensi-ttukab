<?php

namespace app\modules\api2\models;

use Imagine\Image\Box;
use Yii;
use app\models\JadwalKehadiran;
use app\models\Pegawai;
use app\modules\iclock\models\Userinfo;
use yii\imagine\Image;

class Checkinout extends \app\modules\iclock\models\Checkinout
{

    public function fields()
    {
        $fields = parent::fields();

        $fields['nama_pegawai'] = function () {
            return strval(@$this->userinfo->name);
        };

        $fields['nip'] = function () {
            return strval(@$this->userinfo->badgenumber);
        };

        $fields['posisi'] = function () {
            return strval($this->latitude . ', ' . $this->longitude);
        };

        $fields['waktu'] = function () {
            $datetime = \DateTime::createFromFormat('Y-m-d H:i:s', $this->checktime);
            return $datetime->format('H:i:s d-m-Y');
        };

        $fields['status_hadir'] = function () {
            return $this->getStatusHadir();
        };

        return $fields;
    }

    public function getUserIdByUsername()
    {
        $pegawai = Pegawai::findOne(['nip' => $this->nip]);

        $model = Userinfo::find()
            ->andWhere(['badgenumber' => @$pegawai->nip])
            ->one();

        if($model === null) {
            $model = $pegawai->findOrCreateUserInfo();
        }

        if ($model !== null) {
            return $model->userid;
        }
        return null;
    }

    public function getNipByUsername()
    {
        $pegawai = Pegawai::findOne(['nip' => $this->nip]);

        return @$pegawai->nip;
    }

    public function resizeImage()
    {
        $path = Yii::getAlias('@app') . '/web/uploads/checkinout/';
        if (file_exists($path . @$this->foto)) {
            $img = Image::thumbnail($path . @$this->foto, null, 300);
            if($img !== null) {
                $img->save();
            }
            return true;
        }
        echo "tidak ditemukan " . $path . @$this->foto;
    }
}
