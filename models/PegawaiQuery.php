<?php

namespace app\models;

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
        return $this->andWhere(['pegawai.status_hapus' => null]);
    }

    public function setda()
    {
        return $this->andWhere(['pegawai.status_setda' => 1]);
    }

    public function eselonI()
    {
        $this->eselon_condition = array_merge($this->eselon_condition, Eselon::$eselon_i);
        return $this;
    }

    public function eselonII()
    {
        $this->eselon_condition = array_merge($this->eselon_condition, Eselon::$eselon_ii);
        return $this;
    }

    public function eselonIII()
    {
        $this->eselon_condition = array_merge($this->eselon_condition, Eselon::$eselon_iii);
        return $this;
    }

    public function eselonIV()
    {
        $this->eselon_condition = array_merge($this->eselon_condition, Eselon::$eselon_iv);
        return $this;
    }

    public function eselonV()
    {
        $this->eselon_condition[] = Eselon::ESELON_VA;
        return $this;
    }

    /**
     * @param null $db
     * @return \app\models\Pegawai[]
     */
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
