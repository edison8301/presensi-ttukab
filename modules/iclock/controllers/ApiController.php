<?php

namespace app\modules\iclock\controllers;

use app\modules\absensi\models\UploadPresensi;
use app\modules\iclock\models\Userinfo;
use app\modules\iclock\models\Iclock;

class ApiController extends \yii\rest\Controller
{
    public $modelClass = UploadPresensi::class;


}
