<?php

use app\models\User;

/* @var $dasborAbsensi \app\models\DasborAbsensi */

$this->title = "Dasbor Absensi Tahun ".User::getTahun();

echo $this->render('_filter-absensi',['dasborAbsensi'=>$dasborAbsensi]);

if ($dasborAbsensi->id_instansi == null) {
    echo $this->render('_dasbor-absensi-instansi',[
    		'dasborAbsensi' => $dasborAbsensi
    ]);
} else {
    echo $this->render('_dasbor-absensi-pegawai',[
            'dasborAbsensi' => $dasborAbsensi
    ]);
}

