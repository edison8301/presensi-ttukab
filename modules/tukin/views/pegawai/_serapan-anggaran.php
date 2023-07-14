<?php

/* @var $this \yii\web\View */
/* @var $model \app\modules\tukin\models\Pegawai */
/* @var $filter \app\modules\tukin\models\FilterTunjanganForm */

use app\components\Helper; ?>

<div class="box box success">
    <div class="box-header">
        <h3 class="box-title">Serapan Anggaran Instansi Bulan : <?= Helper::getBulanLengkap($filter->bulan); ?></h3>
    </div>
    <div class="box-body">
        <table class="table table-bordered">
            <tr>
                <th style="width: 20%">Instansi </th>
                <td><?= @$model->instansi->nama ?></td>
            </tr>
            <tr>
                <th>Bulan</th>
                <td><?= Helper::getBulanLengkap($filter->bulan) ?></td>
            </tr>
            <tr>
                <th>Serapan</th>
                <td><?= $model->instansi->getSerapanAnggaranBulan($filter->bulan) ?> %</td>
            </tr>
            <tr>
                <th>Potongan</th>
                <td style="font-weight: bold;"><?= $model->instansi->getPotonganSerapanAnggaranBulan($filter->bulan) ?>%</td>
            </tr>
        </table>
    </div>
</div>

