<?php

namespace app\modules\absensi\models;

/**
 * This is the ActiveQuery class for [[InstansiAbsensiManual]].
 *
 * @see InstansiAbsensiManual
 */
class InstansiAbsensiManualQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return InstansiAbsensiManual[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return InstansiAbsensiManual|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
