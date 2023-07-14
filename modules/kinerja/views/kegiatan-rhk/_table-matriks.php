<?php

use app\components\Helper;

/* @var $this yii\web\View */
/* @var $allKegiatanRhkUtama \app\modules\kinerja\models\KegiatanRhk[] */
/* @var $allKegiatanRhkTambahan \app\modules\kinerja\models\KegiatanRhk[] */

?>

<div style="overflow: auto;">
    <table class="table table-bordered" style="table-layout: fixed">
        <tr>
            <th style="text-align: center; width: 50px;" rowspan="3">No</th>
            <th style="text-align: center; width: 300px;" rowspan="3">Rencana Hasil Kerja</th>
            <th style="text-align: center; width: 80px;" rowspan="3">Aspek</th>
            <th style="text-align: center; width: 200px;" rowspan="3">Indikator Kinerja Individu</th>
            <th style="text-align: center; width: 100px;" rowspan="3">Target</th>
            <th style="text-align: center; width: 100px;" rowspan="3">Total Rencana Target</th>
            <th style="text-align: center; width: 100px;" rowspan="3">Total Realisasi</th>
            <th style="text-align: center; width: <?= 60*24 ?>px" colspan="24">Rencana Target / Realisasi Pada Bulan</th>
        </tr>
        <tr>
            <?php for($i=1; $i<=12; $i++) { ?>
                <th style="text-align: center;" colspan="2">
                    <?= Helper::getBulanSingkat($i) ?>
                </th>
            <?php } ?>
        </tr>
        <tr>
            <?php for ($i = 1;$i <= 12;$i++) { ?>
                <th style="text-align:center; width:60px"><span data-toggle="tooltip" title="Rencana Target">&nbsp;TRGT&nbsp;</span></th>
                <th style="text-align:center; width:60px"><span data-toggle="tooltip" title="Realisasi">&nbsp;REAL&nbsp;</span></th>
            <?php } ?>
        </tr>
        <tr>
            <th colspan="31">UTAMA</th>
        </tr>
        <?php $no=1; foreach ($allKegiatanRhkUtama as $kegiatanRhk) { ?>
            <?= $this->render('_tr-kegiatan-rhk-matriks', [
                'kegiatanRhk' => $kegiatanRhk,
                'no' => $no++,
                'level' => 0,
            ]) ?>
        <?php } ?>
        <tr>
            <th colspan="31">TAMBAHAN</th>
        </tr>
        <?php $no=1; foreach ($allKegiatanRhkTambahan as $kegiatanRhk) { ?>
            <?= $this->render('_tr-kegiatan-rhk-matriks', [
                'kegiatanRhk' => $kegiatanRhk,
                'no' => $no++,
                'level' => 0,
            ]) ?>
        <?php } ?>
    </table>
</div>
