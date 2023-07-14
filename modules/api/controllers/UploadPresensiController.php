<?php


namespace app\modules\api\controllers;


use app\modules\absensi\models\UploadPresensi;
use yii\rest\ActiveController;

class UploadPresensiController extends ActiveController
{
    public $modelClass = UploadPresensi::class;
}
