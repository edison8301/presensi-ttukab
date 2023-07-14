<?php

/* @var $kegiatanBulanan \app\modules\kinerja\models\KegiatanBulanan */
/* @var $no int */
/* @var $status_plt bool */

use app\components\Helper;

$kegiatanTahunan = @$kegiatanBulanan->kegiatanTahunan;

?>

<tr>
    <td style="text-align: center"><?= $no; ?></td>
    <td>
        <?= $kegiatanTahunan->kegiatanRhk->nama; ?> <?= @$status_plt ? '(Plt)' : '' ?><br/>
        <i class="fa fa-user"></i> <?= @$kegiatanTahunan->namaPegawai ?><br>
        <i class="fa fa-tag"></i> <?= @$kegiatanTahunan->instansiPegawaiSkp->nomor ?>
    </td>
    <td>
        <?= @$kegiatanTahunan->kegiatanAspek->nama ?>
    </td>
    <td>
        <?= $kegiatanBulanan->kegiatanTahunan->nama; ?>
    </td>
    <td style="text-align: center"><?= $kegiatanBulanan->target; ?></td>
    <td style="text-align: center"><?= $kegiatanBulanan->realisasi; ?></td>
    <td style="text-align: center">
        <?= Helper::rp($kegiatanBulanan->persen_realisasi, 0, 2); ?>%
    </td>
    <td style="text-align: center">
        <?= $kegiatanBulanan->getLinkUpdateRealisasiIcon(); ?>
    </td>
</tr>
