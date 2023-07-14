<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[TunjanganKelas]].
 *
 * @see TunjanganKelas
 */
class TunjanganKelasQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return TunjanganKelas[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return TunjanganKelas|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
