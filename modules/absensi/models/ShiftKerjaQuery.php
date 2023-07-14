<?php

namespace app\modules\absensi\models;

/**
 * This is the ActiveQuery class for [[ShiftKerja]].
 *
 * @see ShiftKerja
 */
class ShiftKerjaQuery extends \yii\db\ActiveQuery
{
    public function aktif($state = true)
    {
        return $this->andWhere(['status_hapus' => !$state]);
    }

}
