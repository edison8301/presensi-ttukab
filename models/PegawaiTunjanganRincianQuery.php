<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[PegawaiTunjanganRincian]].
 *
 * @see PegawaiTunjanganRincian
 */
class PegawaiTunjanganRincianQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return PegawaiTunjanganRincian[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return PegawaiTunjanganRincian|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
