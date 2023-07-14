<?php

use yii\helpers\Html;
use app\components\Helper;

/* @var \app\modules\kinerja\models\KegiatanRhk $kegiatanRhk */
/* @var int $no */
/* @var $level int */

$allKegiatanTahunan = $kegiatanRhk->findAllKegiatanTahunan();

$rowspan = count($allKegiatanTahunan);

if ($rowspan == 0) {
    $rowspan = 1;
}

$kegiatanTahunan = array_shift($allKegiatanTahunan);

?>

<tr>
    <td style="text-align: center;" rowspan="<?= $rowspan ?>">
        <?= $no ?>
    </td>
    <td style="padding-left:<?= 8+$level*20; ?>px;" rowspan="<?= $rowspan ?>">
        <?= $kegiatanRhk->getButtonDropdown(); ?>
        <?= $kegiatanRhk->nama ?><br/>
        <i class="fa fa-tag"></i> <?= @$kegiatanRhk->instansiPegawaiSkp->nomor ?>
    </td>
    <td>
        <?= @$kegiatanTahunan->kegiatanAspek->nama ?>
    </td>
    <td>
        <?= @$kegiatanTahunan->nama ?>
        <?= @$kegiatanTahunan->labelIdKegiatanStatus ?>
    </td>
    <td style="text-align: center;">
        <?= @$kegiatanTahunan->target ?>
        <?= @$kegiatanTahunan->satuan ?>
    </td>
    <td style="text-align: center;">
        <?= @$kegiatanTahunan->totalTarget ?>
        <?= @$kegiatanTahunan->satuan ?>
    </td>
    <td style="text-align: center;">
        <?= @$kegiatanTahunan->totalRealisasi ?>
        <?= @$kegiatanTahunan->satuan ?>
    </td>
    <?php for ($i=1; $i<=12; $i++) { ?>
        <?php
            $kegiatanBulanan = null;
            if ($kegiatanTahunan !== null) {
                $kegiatanBulanan = $kegiatanTahunan->findOrCreateKegiatanBulan($i);
            }
        ?>

        <td style="text-align: center;">
            <?php if ($kegiatanBulanan !== null) { ?>
                <?= $kegiatanBulanan->getEditable([
                    'attribute' => 'target',
                ]); ?>
            <?php } ?>
        </td>
        <td style="text-align: center;">
            <?php if ($kegiatanBulanan !== null) { ?>
                <?= Helper::rp(@$kegiatanBulanan->realisasi, 0); ?>
            <?php } ?>
        </td>
    <?php } ?>
</tr>
<?php foreach ($allKegiatanTahunan as $kegiatanTahunan) { ?>
    <tr>
        <td>
            <?= @$kegiatanTahunan->kegiatanAspek->nama ?>
        </td>
        <td>
            <?= $kegiatanTahunan->nama ?>
            <?= $kegiatanTahunan->getLabelIdKegiatanStatus() ?>
        </td>
        <td style="text-align: center;">
            <?= $kegiatanTahunan->target ?>
            <?= $kegiatanTahunan->satuan ?>
        </td>
        <td style="text-align: center;">
            <?= $kegiatanTahunan->getTotalTarget() ?>
            <?= $kegiatanTahunan->satuan ?>
        </td>
        <td style="text-align: center;">
            <?= $kegiatanTahunan->getTotalRealisasi() ?>
            <?= $kegiatanTahunan->satuan ?>
        </td>
        <?php for ($i=1; $i<=12; $i++) { ?>
            <?php $kegiatanBulanan = $kegiatanTahunan->findOrCreateKegiatanBulan($i); ?>

            <td style="text-align: center;">
                <?= $kegiatanBulanan->getEditable([
                    'attribute' => 'target',
                ]); ?>
            </td>
            <td style="text-align: center;">
                <?= Helper::rp($kegiatanBulanan->realisasi, 0); ?>
            </td>
        <?php } ?>
    </tr>
<?php } ?>

<?php $level++; $i=1; foreach ($kegiatanRhk->findAllSub() as $sub) { ?>
    <?= $this->render('_tr-kegiatan-rhk-matriks', [
        'kegiatanRhk' => $sub,
        'no' => $no . '.' . $i++,
        'level' => $level,
    ]) ?>
<?php } ?>
