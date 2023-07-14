<?php

namespace tests\models;

use Codeception\Specify;
use app\models\Ketidakhadiran;
use app\modules\kinerja\models\KegiatanHarian;

class ValidasiFormTest extends \Codeception\Test\Unit
{
    private $model;

    public function testHarianInputOk()
    {
        $this->model = new KegiatanHarian([
            'tanggal' => '2018-06-26',
            'id_pegawai' => 136,
        ]);

        expect_that($this->model->getIsInRange());
    }

    public function testHarianInputWrong()
    {
        $this->model = new KegiatanHarian([
            'tanggal' => '2018-06-14',
            'id_pegawai' => 136,
        ]);

        expect_not($this->model->getIsInRange());
    }

}
