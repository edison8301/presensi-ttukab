<?php

/* @var $kegiatanRhk \app\modules\kinerja\models\KegiatanRhk */
/* @var $no int */
/* @var $pdf bool|null */
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
    <td rowspan="<?= $rowspan ?>">
        <?= $kegiatanRhk->nama ?><br/>
        <?php if (@$pdf !== true) { ?>
            <?= $kegiatanRhk->getLinkUpdateButton() ?>
            <?= $kegiatanRhk->getLinkDeleteButton() ?>
            <?= $kegiatanRhk->getLinkCreateTahapan() ?>
            <?= $kegiatanRhk->getLinkCreateKegiatanTahunan() ?>
        <?php } ?>
    </td>
    <td>
        <?= @$kegiatanTahunan->nama ?>
        <?php if (@$pdf !== true) { ?>
            <?= @$kegiatanTahunan->labelMappingRpjmd ?>
            <?= @$kegiatanTahunan->keteranganTolak ?>
        <?php } ?>
    </td>
    <td style="text-align: center;">
        <?= @$kegiatanTahunan->target ?>
        <?= @$kegiatanTahunan->satuan ?>
    </td>
    <td style="text-align: center;">
        <?= str_replace(';', '<br/>', @$kegiatanTahunan->perspektif) ?>
    </td>
    <?php if (@$pdf !== true) { ?>
        <td style="text-align: center;">
            <?= @$kegiatanTahunan->labelIdKegiatanStatus ?>
        </td>
        <td style="text-align: center;">
            <?= @$kegiatanTahunan->linkPeriksaV3Icon ?>
            <?= @$kegiatanTahunan->linkSetujuIcon ?>
            <?= @$kegiatanTahunan->linkTolakIcon ?>
            <?= @$kegiatanTahunan->linkKonsepIcon ?>
            <?= @$kegiatanTahunan->linkViewCatatanIcon ?>
            <?= @$kegiatanTahunan->linkViewV3Icon ?>
            <?= @$kegiatanTahunan->linkUpdateV3Icon ?>
            <?= @$kegiatanTahunan->linkdeleteIcon ?>
        </td>
    <?php } ?>
</tr>
<?php foreach ($allKegiatanTahunan as $kegiatanTahunan) { ?>
    <tr>
        <td>
            <?= $kegiatanTahunan->nama ?>
            <?php if (@$pdf !== true) { ?>
                <?= $kegiatanTahunan->getLabelMappingRpjmd() ?>
                <?= $kegiatanTahunan->getKeteranganTolak() ?>
            <?php } ?>
        </td>
        <td style="text-align: center;">
            <?= $kegiatanTahunan->target ?>
            <?= $kegiatanTahunan->satuan ?>
        </td>
        <td style="text-align: center;">
            <?= str_replace(';', '<br/>', @$kegiatanTahunan->perspektif) ?>
        </td>
        <?php if (@$pdf !== true) { ?>
            <td style="text-align: center;">
                <?= $kegiatanTahunan->getLabelIdKegiatanStatus() ?>
            </td>
            <td style="text-align: center;">
                <?= $kegiatanTahunan->getLinkPeriksaV3Icon() ?>
                <?= $kegiatanTahunan->getLinkSetujuIcon() ?>
                <?= $kegiatanTahunan->getLinkTolakIcon() ?>
                <?= $kegiatanTahunan->getLinkKonsepIcon() ?>
                <?= $kegiatanTahunan->getLinkViewCatatanIcon() ?>
                <?= $kegiatanTahunan->getLinkViewV3Icon() ?>
                <?= $kegiatanTahunan->getLinkUpdateV3Icon() ?>
                <?= $kegiatanTahunan->getLinkDeleteIcon() ?>
            </td>
        <?php } ?>
    </tr>
<?php } ?>

<?php if (@$pdf !== true) { ?>
    <?php $level++; $i=1; foreach ($kegiatanRhk->findAllSub() as $sub) { ?>
        <?= $this->render('_tr-kegiatan-rhk-jpt', [
            'kegiatanRhk' => $sub,
            'no' => $no . '.' . $i++,
            'level' => $level,
            'pdf' => @$pdf,
        ]) ?>
    <?php } ?>
<?php } ?>
