<?php

namespace app\modules\absensi\models;

/**
 * This is the ActiveQuery class for [[HukumanDisiplin]].
 *
 * @see HukumanDisiplin
 */
class HukumanDisiplinQuery extends \yii\db\ActiveQuery
{
    public function aktif($state = true)
    {
        return $this->andWhere(['status_hapus' => !$state]);
    }
}
