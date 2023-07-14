<?php

namespace app\modules\absensi\models;

/**
 * This is the ActiveQuery class for [[KetidakhadiranJenis]].
 *
 * @see KetidakhadiranJenis
 */
class KetidakhadiranJenisQuery extends \yii\db\ActiveQuery
{
    public function aktif()
    {
        return $this->andWhere('[[status_aktif]]=1');
    }
}
