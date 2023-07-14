<?php

use app\components\Helper;
?>

<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th style="text-align: center;width:10px;">No.</th>
            <th style="text-align: center">Aspek</th>
            <th style="text-align: center">Target</th>
            <th style="text-align: center">Realisasi</th>
        </tr>
    </thead>
    <tr>
        <td style="text-align: center;">1.</td>
        <td style="text-align: left;">Kuantitas</td>
        <td style="text-align: center;"><?= $model->target ?> <?= $model->getSatuanKuantitas() ?></td>
        <td style="text-align: center;"><?= Helper::rp($model->realisasi,0); ?> <?= $model->getSatuanKuantitas() ?></td>
    </tr>
    <tr>
        <td style="text-align: center;">2.</td>
        <td style="text-align: left;">Kualitas</td>
        <td style="text-align: center;"><?= $model->getTargetSatuanKualitas() ?></td>
        <td style="text-align: center;">
            <?= Helper::rp($model->realisasi_kualitas, 0) ?>
            <?= @$model->kegiatanTahunan->satuan_kualitas ?>
        </td>
    </tr>
    <tr>
        <td style="text-align: center;">3.</td>
        <td style="text-align: left;">Waktu</td>
        <td style="text-align: center;"><?= $model->getTargetSatuanWaktu() ?></td>
        <td style="text-align: center;">
            <?= Helper::rp($model->realisasi_waktu, 0) ?>
            <?= @$model->kegiatanTahunan->satuan_waktu ?>
        </td>
    </tr>
    <tr>
        <td style="text-align: center;">4.</td>
        <td style="text-align: left;">Biaya</td>
        <td style="text-align: center;"><?= $model->getTargetSatuanBiaya() ?></td>
        <td style="text-align: center;">
            <?= Helper::rp($model->realisasi_biaya, 0) ?>
            <?= @$model->kegiatanTahunan->satuan_biaya ?>
        </td>
    </tr>
</table>