<?php

use app\modules\kinerja\models\KegiatanRhkJenis;
use yii\helpers\ArrayHelper;

/* @var $this \yii\web\View */
/* @var $instansiPegawai InstansiPegawai */

$allKegiatanRhk = [];

if ($instansiPegawai !== null AND $instansiPegawai->pegawai !== null) {
    $allKegiatanRhk = $instansiPegawai->pegawai->findAllKegiatanRhk([
        'id_kegiatan_rhk_jenis' => KegiatanRhkJenis::UTAMA,
        'id_induk_is_null' => true,
        'id_instansi_pegawai' => $instansiPegawai->id,
    ]);
}

$arrayIdKegiatanRhkAtasan = ArrayHelper::map($allKegiatanRhk, 'id', 'id');

$colspan = count($allKegiatanRhk);
$kegiatanRhk = array_shift($allKegiatanRhk);

if ($colspan == 0) {
    $colspan = 1;
}

?>

<table class="table table-bordered table-condensed" style="table-layout: fixed; font-size: 12px;">
    <thead>
        <tr>
            <th style="text-align: center; width: 200px; background-color: #B8CCE4">Pegawai</th>
            <th style="text-align: center; width: 200px; background-color: #B8CCE4">Jabatan</th>
            <th style="text-align: center; width: <?= $colspan * 300 ?>px; background-color: #B8CCE4" colspan="<?= $colspan ?>">Output Antara/Output/Output Layanan</th>
        </tr>
        <tr>
            <th style="vertical-align: top; background-color: #DCE6F1"><?= @$instansiPegawai->pegawai->nama ?></th>
            <th style="vertical-align: top; background-color: #DCE6F1"><?= @$instansiPegawai->namaJabatan ?></th>
            <th style="vertical-align: top; background-color: #DCE6F1"><?= @$kegiatanRhk->nama ?></th>
            <?php foreach ($allKegiatanRhk as $kegiatanRhk) { ?>
                <th style="vertical-align: top; background-color: #DCE6F1"><?= $kegiatanRhk->nama ?></th>
            <?php } ?>
        </tr>
    </thead>
    <?php foreach (@$instansiPegawai->manyInstansiPegawaiBawahan as $instansiPegawaiBawahan) { ?>
        <?= $this->render('_tr-matriks-peran-hasil', [
            'instansiPegawai' => $instansiPegawaiBawahan,
            'arrayIdKegiatanRhkAtasan' => $arrayIdKegiatanRhkAtasan,
        ]) ?>
    <?php } ?>
</table>