<?php

namespace app\modules\tukin\models;

/**
 * This is the ActiveQuery class for [[RefVariabelObjektif]].
 *
 * @see RefVariabelObjektif
 */
class RefVariabelObjektifQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return RefVariabelObjektif[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return RefVariabelObjektif|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
