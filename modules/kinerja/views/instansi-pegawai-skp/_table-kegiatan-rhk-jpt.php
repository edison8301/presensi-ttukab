<?php

/* @var $this \yii\web\View */
/* @var $allKegiatanRhkUtama \app\modules\kinerja\models\KegiatanRhk[] */
/* @var $allKegiatanRhkTambahan \app\modules\kinerja\models\KegiatanRhk[] */

?>

<div style="overflow-x: auto;">
    <table class="table table-bordered">
        <tr>
            <th style="text-align: center; width: 3%;">No</th>
            <th style="text-align: center; min-width: 300px;">Rencana Hasil Kerja</th>
            <th style="text-align: center; min-width: 300px">Indikator Kinerja Individu</th>
            <th style="text-align: center; width: 100px;">Target</th>
            <th style="text-align: center;">Perspektif</th>
            <th style="text-align: center; width: 80px;">Status</th>
            <th style="width: 80px;"></th>
        </tr>
        <tr>
            <th colspan="7">Utama</th>
        </tr>
        <?php $no=1; foreach ($allKegiatanRhkUtama as $kegiatanRhk) { ?>
            <?= $this->render('_tr-kegiatan-rhk-jpt', [
                'kegiatanRhk' => $kegiatanRhk,
                'no' => $no++,
                'level' => 0,
            ]); ?>
        <?php } ?>
        <tr>
            <th colspan="7">Tambahan</th>
        </tr>
        <?php $no=1; foreach ($allKegiatanRhkTambahan as $kegiatanRhk) { ?>
            <?= $this->render('_tr-kegiatan-rhk-jpt', [
                'kegiatanRhk' => $kegiatanRhk,
                'no' => $no++,
                'level' => 0,
            ]); ?>
        <?php } ?>
    </table>
</div>
