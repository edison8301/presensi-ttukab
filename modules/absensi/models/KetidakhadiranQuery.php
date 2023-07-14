<?php

namespace app\modules\absensi\models;

/**
 * This is the ActiveQuery class for [[Ketidakhadiran]].
 *
 * @see Ketidakhadiran
 */
class KetidakhadiranQuery extends \yii\db\ActiveQuery
{
    public function setuju()
    {
        return $this->andWhere(['id_ketidakhadiran_status' => KetidakhadiranStatus::SETUJU]);
    }

    public function proses()
    {
        return $this->andWhere(['id_ketidakhadiran_status' => KetidakhadiranStatus::PROSES]);
    }

    public function tolak()
    {
        return $this->andWhere(['id_ketidakhadiran_status' => KetidakhadiranStatus::TOLAK]);
    }
}
