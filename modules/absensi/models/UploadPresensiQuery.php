<?php

namespace app\modules\absensi\models;

/**
 * This is the ActiveQuery class for [[UploadPresensi]].
 *
 * @see UploadPresensi
 */
class UploadPresensiQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return UploadPresensi[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return UploadPresensi|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
