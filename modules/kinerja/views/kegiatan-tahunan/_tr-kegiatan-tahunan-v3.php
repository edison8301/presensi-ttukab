<?php

use app\modules\kinerja\models\KegiatanStatus;
use yii\helpers\Html;

/* @var $kegiatanTahunan \app\modules\kinerja\models\KegiatanTahunan */
/* @var $no int */

?>

<tr>
    <td style="text-align: center;">
        <?php if($kegiatanTahunan->id_kegiatan_status == KegiatanStatus::PERIKSA) {
            echo Html::checkbox('selection[]', false, [
                'value' => $kegiatanTahunan->id,
                'class' => 'checkbox-data',
            ]);
        } ?>
    </td>
    <td style="text-align: center">
        <?= $no ?>
    </td>
    <td>
        <?= @$kegiatanTahunan->kegiatanRhk->kegiatanRhkAtasan->nama ?>
    </td>
    <td>
        <?= @$kegiatanTahunan->kegiatanRhk->nama ?><br>
        <i class="fa fa-user"></i> <?= @$kegiatanTahunan->pegawai->nama; ?><br>
        <i class="fa fa-tag"></i> <?= @$kegiatanTahunan->instansiPegawaiSkp->nomor; ?><br>
    </td>
    <td>
        <?= @$kegiatanTahunan->kegiatanAspek->nama ?>
    </td>
    <td>
        <?= $kegiatanTahunan->nama ?>
    </td>
    <td style="text-align: center;">
        <?= $kegiatanTahunan->target ?>
        <?= $kegiatanTahunan->satuan ?>
    </td>
    <td style="text-align: center;">
        <?= $kegiatanTahunan->getLabelIdKegiatanStatus() ?>
    </td>
    <td style="text-align: center;">
        <?= $kegiatanTahunan->getLinkSetujuIcon() ?>
        <?= $kegiatanTahunan->getLinkTolakIcon() ?>
        <?= $kegiatanTahunan->getLinkKonsepIcon(); ?>
        <?= $kegiatanTahunan->getLinkViewCatatanIcon() ?>
        <?= $kegiatanTahunan->getLinkViewV3Icon() ?>
    </td>
</tr>
