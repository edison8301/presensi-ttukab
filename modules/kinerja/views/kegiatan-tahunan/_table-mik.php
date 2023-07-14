<?php

use app\modules\kinerja\models\SkpIkiMik;

/* @var $skpIkiMik \app\modules\kinerja\models\SkpIkiMik */

?>

<table class="table table-bordered">
    <tr>
        <th style="width: 300px;">Rencana Hasil Kerja</th>
        <th style="vertical-align: top;" colspan="4">
            <?= @$skpIkiMik->skpIki->kegiatanRhk->nama ?>
        </th>
    </tr>
    <tr>
        <th>UKURAN KEBERHASILAN/INDIKATOR KINERJA DAN TARGET</th>
        <th style="vertical-align: top;" colspan="4">
            <?= @$skpIkiMik->skpIki->nama ?>
        </th>
    </tr>
    <tr>
        <th>TUJUAN</th>
        <td colspan="4">
            <?= str_replace("\n", '<br/>', $skpIkiMik->tujuan) ?>
        </td>
    </tr>
    <tr>
        <th rowspan="4">DESKRIPSI</th>
        <th colspan="4">Definisi</th>
    </tr>
    <tr>
        <td colspan="4">
            <?= str_replace("\n", '<br/>', $skpIkiMik->definisi) ?>&nbsp;
        </td>
    </tr>
    <tr>
        <th colspan="4">Formula</th>
    </tr>
    <tr>
        <td colspan="4">
            <?= str_replace("\n", '<br/>', $skpIkiMik->formula) ?>
        </td>
    </tr>
    <tr>
        <th>SATUAN PENGUKURAN (Opsional bagi pendekatan hasil kerja kualitatif)</th>
        <td colspan="4">
            <?= str_replace("\n", '<br/>', $skpIkiMik->satuan_pengukuran) ?>
        </td>
    </tr>
    <tr>
        <th>KUALITAS DAN TINGKAT KENDALI</th>
        <?php foreach (SkpIkiMik::getListKualitasTingkatKendali() as $value) { ?>
            <?php $colspan = $value == 'Output Kendali Rendah' ? 2 : 1 ?>
            <?php $checked = $value == $skpIkiMik->kualitas_tingkat_kendali; ?>
            <td colspan="<?= $colspan ?>" style="font-weight: <?= $checked ? 'bold' : 'normal' ?>;">
                (<?= $checked ? 'x' : '' ?>)
                <?= $value ?>
            </td>
        <?php } ?>
    </tr>
    <tr>
        <th>SUMBER DATA</th>
        <td colspan="4">
            <?= str_replace("\n", '<br/>', $skpIkiMik->sumber_data) ?>
        </td>
    </tr>
    <tr>
        <th>PERIODE PELAPORAN</th>
        <?php foreach (SkpIkiMik::getListPeriodePelaporan() as $value) { ?>
            <?php $checked = $value == $skpIkiMik->periode_pelaporan; ?>
            <td style="font-weight: <?= $checked ? 'bold' : 'normal' ?>;">
                (<?= $checked ? 'x' : '' ?>)
                <?= $value ?>
            </td>
        <?php } ?>
    </tr>
</table>
