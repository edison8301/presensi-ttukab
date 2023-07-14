<?php

namespace app\modules\absensi\models;

/**
 * This is the ActiveQuery class for [[PegawaiShiftKerja]].
 *
 * @see PegawaiShiftKerja
 */
class PegawaiShiftKerjaQuery extends \yii\db\ActiveQuery
{
    public function aktif()
    {
        return $this->andWhere('status_hapus IS NULL');
    }
}
