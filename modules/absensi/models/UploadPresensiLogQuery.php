<?php

namespace app\modules\absensi\models;

/**
 * This is the ActiveQuery class for [[UploadPresensiLog]].
 *
 * @see UploadPresensiLog
 */
class UploadPresensiLogQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return UploadPresensiLog[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return UploadPresensiLog|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
