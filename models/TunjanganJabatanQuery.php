<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[TunjanganJabatan]].
 *
 * @see TunjanganJabatan
 */
class TunjanganJabatanQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return TunjanganJabatan[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return TunjanganJabatan|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
