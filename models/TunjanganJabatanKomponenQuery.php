<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[TunjanganJabatanKomponen]].
 *
 * @see TunjanganJabatanKomponen
 */
class TunjanganJabatanKomponenQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return TunjanganJabatanKomponen[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return TunjanganJabatanKomponen|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
