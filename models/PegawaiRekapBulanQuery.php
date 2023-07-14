<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[PegawaiRekapBulan]].
 *
 * @see PegawaiRekapBulan
 */
class PegawaiRekapBulanQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return PegawaiRekapBulan[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return PegawaiRekapBulan|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
