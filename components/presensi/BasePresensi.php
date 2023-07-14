<?php

namespace app\components\presensi;

use app\components\Helper;
use yii\base\BaseObject;

/**
 *
 * @property DateTime $date
 * @property string $hari
 * @property string $tanggal
 */
abstract class BasePresensi extends BaseObject
{
    /**
     * @return DateTime
     */
    abstract public function getDate();

    /**
     * @return string
     */
    public function getTanggal()
    {
        return $this->date->format("Y-m-d");
    }

    /**
     * @return string
     */
    public function getHari()
    {
        return Helper::getHari($this->tanggal);
    }
}
