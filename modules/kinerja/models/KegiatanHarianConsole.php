<?php

namespace app\modules\kinerja\models;

use Yii;

/**
*
*/
class KegiatanHarianConsole extends KegiatanHarian
{
    public function afterSave($insert,$changedAttributes)
    {
        return true;
    }

    public function beforeSoftDelete()
    {
        return true;
    }

    public function beforeDelete()
    {
        return true;
    }
}
