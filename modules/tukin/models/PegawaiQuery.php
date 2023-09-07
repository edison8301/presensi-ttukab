<?php

namespace app\modules\tukin\models;

/**
 * This is the ActiveQuery class for [[Pegawai]].
 *
 * @see Pegawai
 */
class PegawaiQuery extends \yii\db\ActiveQuery
{
    public $eselon_condition = [];

    public function aktif()
    {
        //return $this->andWhere(['pegawai.status_hapus' => null]);
    }

    public function all($db = null)
    {
        if ($this->eselon_condition !== []) {
            $this->andWhere(['in', 'id_eselon', $this->eselon_condition]);
        }
        return parent::all($db);
    }

    public function one($db = null)
    {
        if ($this->eselon_condition !== []) {
            $this->andWhere(['in', 'id_eselon', $this->eselon_condition]);
        }
        return parent::one($db);
    }
}
