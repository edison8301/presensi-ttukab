<?php

namespace app\modules\tukin\models;

use Yii;

class PegawaiSumber extends Pegawai
{
    public static function getDb()
    {
        return Yii::$app->get('db');
    }

}
