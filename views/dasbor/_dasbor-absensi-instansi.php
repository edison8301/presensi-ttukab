<?php

use yii\helpers\Html;

?>

<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Daftar OPD</h3>
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
        <?php foreach(\app\models\Instansi::find()->all() as $data) { ?>
        <?php $latestModel = $data->getCheckinoutTerakhir(); ?>
        <tr>
            <td style="text-align:center"><?= $i; ?></td>
            <td><?= Html::a($data->nama,['/dasbor/absensi-admin','DasborAbsensi[id_instansi]'=>$data->id]); ?></td>
            <td style="text-align:center"><?= $data->getCountManyPegawai(); ?> Pegawai</td>
            <td style="text-align:center"><?= $data->countCheckinout(); ?> Absen</td>
            <td style="text-align:center"><?= $latestModel !== null ? $latestModel->checktime : null; ?></td>
            <td style="text-align:center"><?= $latestModel !== null ? $latestModel->getKeterangan() : null; ?></td>
        </tr>
        <?php $i++; } ?>
        </table>
    </div>

</div>
