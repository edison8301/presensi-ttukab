<?php

use \app\models\User;

$this->title = "Dashboard Kinerja";

echo $this->render('_filter');

if (User::getUnitKerja() == null) {
    echo $this->render('_opd');
} else {
    echo $this->render('_pegawai');
}
