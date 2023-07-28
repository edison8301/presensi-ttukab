<?php


namespace app\components;


use Yii;

class Config
{
    const PERHITUNGAN_PERGUB_2022 = true;

    public static function getUrlDebian()
    {
        return @Yii::$app->params['urlDebian'];
    }

    public static function pengumumanAktif()
    {
        return @Yii::$app->params['pengumumanAktif'];
    }

    public static function urlAnjab()
    {
        return @Yii::$app->params['urlAnjab'];
    }
}
