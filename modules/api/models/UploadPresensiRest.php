<?php

namespace app\modules\api\models;

use app\modules\absensi\models\UploadPresensi;
use yii\web\UploadedFile;

class UploadPresensiRest extends UploadPresensi
{
    public function upload($token = null)
    {
        if ($this->fileInstance instanceof UploadedFile && $this->validate()) {
            $this->fileInstance->saveAs("uploads/{$this->file}");
            return true;
        }
        return false;
    }

}
