<?php

namespace app\modules\api2\models;

class Unit extends \app\models\Unit
{
    public function fields()
    {
        $fields = [];

        $fields['id'] = function () {
            return $this->id;
        };

        $fields['kode'] = function () {
            return $this->kode;
        };

        $fields['nama'] = function () {
            return $this->nama;
        };

        return $fields;
    }
}
