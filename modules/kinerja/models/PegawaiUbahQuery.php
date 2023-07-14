<?php

namespace app\modules\kinerja\models;

/**
 * This is the ActiveQuery class for [[PegawaiUbah]].
 *
 * @see PegawaiUbah
 */
class PegawaiUbahQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return PegawaiUbah[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return PegawaiUbah|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
