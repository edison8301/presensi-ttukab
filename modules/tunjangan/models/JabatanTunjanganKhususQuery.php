<?php

namespace app\modules\tunjangan\models;

/**
 * This is the ActiveQuery class for [[JabatanTunjanganKhusus]].
 *
 * @see JabatanTunjanganKhusus
 */
class JabatanTunjanganKhususQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    public function berlaku($tanggal=null)
    {
        if ($tanggal == null) {
            $tanggal = date('Y-m-d');
        }

        return $this->andWhere('jabatan_tunjangan_khusus.tanggal_mulai <= :tanggal AND jabatan_tunjangan_khusus.tanggal_selesai >= :tanggal',[
            ':tanggal'=>$tanggal
        ]);
    }

    /**
     * {@inheritdoc}
     * @return JabatanTunjanganKhusus[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return JabatanTunjanganKhusus|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
