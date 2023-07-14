<?php

/* @var $this yii\web\View */
/* @var $allKegiatanRhkUtama \app\modules\kinerja\models\KegiatanRhk[] */
/* @var $allKegiatanRhkTambahan \app\modules\kinerja\models\KegiatanRhk[] */
/* @var $pdf bool|null */

$colspan = 8;

if (@$pdf === true) {
    $colspan = 6;
}

?>

<div style="overflow-x: auto">
    <table class="table table-bordered">
        <tr>
            <th style="text-align: center; width: 50px;">No</th>
            <th style="text-align: center; width: 250px;">Rencana Hasil Kerja Atasan Yang Diintervensi</th>
            <th style="text-align: center; width: 300px;">Rencana Hasil Kerja</th>
            <th style="text-align: center; width: 80px;">Aspek</th>
            <th style="text-align: center;">Indikator Kinerja Individu</th>
            <th style="text-align: center; width: 100px;">Target</th>
            <?php if (@$pdf !== true) { ?>
                <th style="text-align: center; width: 80px;">Status</th>
                <th style="width: 80px;"></th>
            <?php } ?>
        </tr>
        <tr>
            <th colspan="<?= $colspan ?>">Utama</th>
        </tr>
        <?php $no=1; foreach ($allKegiatanRhkUtama as $kegiatanRhk) { ?>
            <?= $this->render('_tr-kegiatan-rhk-non-jpt', [
                'kegiatanRhk' => $kegiatanRhk,
                'no' => $no++,
                'level' => 0,
                'pdf' => @$pdf,
            ]) ?>
        <?php } ?>
        <tr>
            <th colspan="<?= $colspan ?>">Tambahan</th>
        </tr>
        <?php $no=1; foreach ($allKegiatanRhkTambahan as $kegiatanRhk) { ?>
            <?= $this->render('_tr-kegiatan-rhk-non-jpt', [
                'kegiatanRhk' => $kegiatanRhk,
                'no' => $no++,
                'level' => 0,
                'pdf' => @$pdf,
            ]) ?>
        <?php } ?>
    </table>
</div>
