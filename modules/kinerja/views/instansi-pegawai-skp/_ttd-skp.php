<?php

use app\components\Helper;

/* @var $model \app\modules\kinerja\models\InstansiPegawaiSkp */

$datetime = \DateTime::createFromFormat('Y-m-d', substr($model->tanggal_mulai, 0, 10));
$bulan = $datetime->format('n');

?>

<table class="padding-0" style="text-align: center;">
    <tr>
        <td style="width: 50%"></td>
        <td style="width: 50%">
            Pangkalpinang,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= Helper::getBulanLengkap($bulan) ?> <?= $model->tahun ?>
        </td>
    </tr>
    <tr>
        <td>Pegawai yang Dinilai</td>
        <td>Pejabat Penilai Kinerja</td>
    </tr>
    <tr>
        <td style="height: 100px;"></td>
        <td></td>
    </tr>
    <tr>
        <td style="text-decoration: underline;">
            <?= @$model->instansiPegawai->pegawai->nama ?>
        </td>
        <td style="text-decoration: underline;">
            <?php if ($model->isJpt() === false) { ?>
                <?= @$model->instansiPegawai->atasan->nama ?>
            <?php } ?>

            <?php if ($model->isJpt() === true) { ?>
                Dr. Ir. RIDWAN DJAMALUDIN, M.Sc
            <?php } ?>
        </td>
    </tr>
    <tr>
        <td>NIP.<?= @$model->instansiPegawai->pegawai->nip ?></td>
        <td>
            <?php if ($model->isJpt() === false) { ?>
                NIP.<?= @$model->instansiPegawai->atasan->nip ?>
            <?php } ?>
        </td>
    </tr>
</table>
