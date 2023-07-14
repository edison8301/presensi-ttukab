<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[PegawaiTunjangan]].
 *
 * @see PegawaiTunjangan
 */
class PegawaiTunjanganQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return PegawaiTunjangan[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return PegawaiTunjangan|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
