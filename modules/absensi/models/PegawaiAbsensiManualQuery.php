<?php

namespace app\modules\absensi\models;

/**
 * This is the ActiveQuery class for [[PegawaiAbsensiManual]].
 *
 * @see PegawaiAbsensiManual
 */
class PegawaiAbsensiManualQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return PegawaiAbsensiManual[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return PegawaiAbsensiManual|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
