<?php

/**
 * Created by PhpStorm.
 * User: iqbal
 * Date: 12/27/2018
 * Time: 11:08 AM
 */

/* @var $this \yii\web\View */
/* @var $model \app\modules\tukin\models\Pegawai */
/* @var $rekap \app\modules\tukin\models\PegawaiRekapTunjangan */

use app\components\Helper;
?>

<div class="box box-info">
    <div class="box-header with-title">
        <h3 class="box-title">
            Instansi Kordinatif
        </h3>
    </div>
    <div class="box-body">
        <table class="table table-bordered">
            <tr>
                <th>Instansi</th>
                <td>:</td>
                <td><?= $model->instansi->nama ?></td>
            </tr>
            <tr>
                <th>Kelompok</th>
                <td>:</td>
                <td><?= $model->instansiKordinatifBesaran->getKelompok() ?></td>
            </tr>
            <tr>
                <th>Besaran</th>
                <td>:</td>
                <td><?= Helper::rp($model->instansiKordinatifBesaran->besaran) ?></td>
            </tr>
        </table>
    </div>
</div>
