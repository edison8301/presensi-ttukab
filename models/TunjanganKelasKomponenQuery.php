<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[TunjanganKelasKomponen]].
 *
 * @see TunjanganKelasKomponen
 */
class TunjanganKelasKomponenQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return TunjanganKelasKomponen[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return TunjanganKelasKomponen|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
