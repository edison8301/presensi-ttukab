<?php

use app\modules\absensi\models\Instansi;
use yii\helpers\Html;

?>

<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Daftar SKPD</h3>
    </div>

    <div class="box-body">
        <table class="table table-bordered">
        <thead>
        <tr>
            <th style="text-align: center">No</th>
            <th style="text-align: left">Nama OPD</th>
            <th style="text-align: center">Jumlah<br>Pegawai</th>
            <th style="text-align: center">Jumlah<br>Absensi</th>
            <th style="text-align: center">Absensi<br>Terakhir</th>
            <th style="text-align: center">Keterangan</th>
        </tr>
        </thead>
        <?php $i=1; ?>
        <?php foreach(Instansi::find()->all() as $data) { ?>
        <?php $latestModel = $data->getCheckinoutTerakhir(); ?>
        <tr>
            <td style="text-align:center"><?= $i; ?></td>
            <td><?= Html::a($data->nama,['/absensi/dasbor/','Dasbor[id_instansi]'=>$data->id]); ?></td>
            <td style="text-align:center"><?= $data->getCountManyPegawai(); ?> Pegawai</td>
            <td style="text-align:center"><?= $data->countCheckinout(); ?> Absensi</td>
            <td style="text-align:center"><?= $latestModel !== null ? $latestModel->checktime : null; ?></td>
            <td style="text-align:center"><?= $latestModel !== null ? $latestModel->getKeterangan() : null; ?></td>
        </tr>
        <?php $i++; } ?>
        </table>
    </div>
</div>