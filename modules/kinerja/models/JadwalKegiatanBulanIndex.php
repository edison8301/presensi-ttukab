<?php


namespace app\modules\kinerja\models;


use yii\base\Model;

/**
 *
 * @property JadwalKegiatanBulan[] $allModels
 * @property mixed $tahun
 */
class JadwalKegiatanBulanIndex extends Model
{
    public function getTahun()
    {
        return \app\models\User::getTahun();
    }

    private $_models;
    /**
     * @return JadwalKegiatanBulan[]
     */
    public function getAllModels()
    {
        if ($this->_models === null) {
            for ($i = 1; $i <= 12; $i++) {
                $this->_models[] = JadwalKegiatanBulan::findOrCreate($i, $this->tahun);
            }
        }
        return $this->_models;
    }
}
