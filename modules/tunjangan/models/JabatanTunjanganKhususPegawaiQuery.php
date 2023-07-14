<?php

namespace app\modules\tunjangan\models;

/**
 * This is the ActiveQuery class for [[JabatanTunjanganKhususPegawai]].
 *
 * @see JabatanTunjanganKhususPegawai
 */
class JabatanTunjanganKhususPegawaiQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return JabatanTunjanganKhususPegawai[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return JabatanTunjanganKhususPegawai|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
