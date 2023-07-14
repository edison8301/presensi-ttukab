<?php

namespace app\components\kinerja;

use app\components\Helper;
use DateTime;
use yii\base\BaseObject;

/**
 *
 * @property DateTime $date
 * @property string $hari
 * @property string $tanggal
 */
abstract class BaseKinerja extends BaseObject
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
