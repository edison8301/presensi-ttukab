<?php

namespace app\modules\api2\models;

class Peta extends \app\models\Peta
{
    public function setLatlong()
    {
        if ($this->latitude == null OR $this->longitude == null) {
            return;
        }

        if ($this->latitude == 'null' OR $this->longitude == 'null') {
            $this->latitude = null;
            $this->longitude = null;
            return;
        }

        $this->latlong = "$this->latitude, $this->longitude";
        return $this->latlong;
    }
}
