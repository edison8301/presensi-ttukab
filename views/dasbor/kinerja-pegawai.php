<?php

/* @var $this yii\web\View */
/* @var $dasborKinerja \app\models\DasborKinerja */

?>

<?= $this->render('_filter-kinerja',['dasborKinerja'=>$dasborKinerja]); ?>

<?= $this->render('_rekap-kegiatan-harian',['dasborKinerja'=>$dasborKinerja]); ?>

<?= $this->render('_rekap-kegiatan-bulanan',['dasborKinerja'=>$dasborKinerja]); ?>

<?= $this->render('_rekap-kegiatan-tahunan',['dasborKinerja'=>$dasborKinerja]); ?>
