<?php



/* @var $this \yii\web\View */

use app\components\Helper;
use app\models\InstansiPegawai;

/* @var $querySearch \app\models\InstansiPegawaiQuery */
/* @var $searchModel \app\models\InstansiPegawaiSearch */
?>
<div style="text-align: center">
    <h3>Rekap IKI</h3>
</div>

<div>
    <table class="table">
        <tr>
            <td style="width: 130px">Nama Instansi</td>
            <td style="width: 20px">:</td>
            <td><?= $searchModel->instansi->nama ?></td>
        </tr>
        <tr>
            <td>Bulan</td>
            <td>:</td>
            <td><?= Helper::getBulanLengkap($searchModel->bulan) ?></td>
        </tr>
    </table>
</div>

<table class="table table-bordered" cellpadding="2">
    <thead>
    <tr>
        <th style="text-align: center">No</th>
        <th style="text-align: center">Nama</th>
        <th style="text-align: center">Gol</th>
        <th style="text-align: center">Eselon</th>
        <th style="text-align: center">Jabatan</th>
        <th style="text-align: center">Jenis Jabatan</th>
        <th style="text-align: center">Keterangan</th>
    </tr>
    </thead>
    <?php $i = 1 ?>
    <?php foreach ($querySearch->all() as $instansiPegawai) { ?>
        <?php /** @var InstansiPegawai $instansiPegawai */ ?>
        <tr>
            <td style="text-align: center"><?= $i++ ?></td>
            <td><?= $instansiPegawai->pegawai->nama ?></td>
            <td style="text-align: center"><?= @$instansiPegawai->pegawai->golongan->golongan ?></td>
            <td style="text-align: center"><?= @$instansiPegawai->eselon->nama ?></td>
            <td><?= $instansiPegawai->getNamaJabatan(false) ?></td>
            <td style="text-align: center"><?= $instansiPegawai->getJenisJabatan() ?></td>
            <td style="text-align: center">
                <?= $instansiPegawai->isMengisiIki() ? '&#x2714;' : '&times;' ?>
            </td>
        </tr>
    <?php } ?>
</table>
