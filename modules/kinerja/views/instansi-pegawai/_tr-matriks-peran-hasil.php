<?php 

use app\modules\kinerja\models\KegiatanRhkJenis;

/* @var $this \yii\web\View */
/* @var $instansiPegawai InstansiPegawai */
/* @var $arrayIdKegiatanRhkAtasan array */

$listKegiatanRhkAtasanAwal = [];
$listKegiatanRhkAtasanSisa = [];

$rowspan = 0;

foreach ($arrayIdKegiatanRhkAtasan as $id_kegiatan_rhk_atasan) {

    $allKegiatanRhk = $instansiPegawai->pegawai->findAllKegiatanRhk([
        'id_kegiatan_rhk_atasan' =>  $id_kegiatan_rhk_atasan,
        'id_kegiatan_rhk_jenis' => KegiatanRhkJenis::UTAMA,
        'id_induk_is_null' => true,
        'id_instansi_pegawai' => $instansiPegawai->id,
    ]);

    if (count($allKegiatanRhk) > $rowspan) {
        $rowspan = count($allKegiatanRhk);
    }

    $listKegiatanRhkAtasanAwal[$id_kegiatan_rhk_atasan] = array_shift($allKegiatanRhk);
    $listKegiatanRhkAtasanSisa[$id_kegiatan_rhk_atasan] = $allKegiatanRhk;
}

if ($rowspan == 0) {
    $rowspan = 1;
}

?>

<tr>
    <td rowspan="<?= $rowspan ?>">
        <?= @$instansiPegawai->pegawai->nama ?>
    </td>
    <td rowspan="<?= $rowspan ?>">
        <?= $instansiPegawai->getNamaJabatan() ?>
    </td>
    <?php foreach ($listKegiatanRhkAtasanAwal as $kegiatanRhk) { ?>
        <td><?= @$kegiatanRhk->nama ?></td>
    <?php } ?>
</tr>

<?php for ($i=0; $i < ($rowspan-1); $i++) { ?>
    <tr>
        <?php foreach ($arrayIdKegiatanRhkAtasan as $id_kegiatan_rhk_atasan) { ?>
            <?php $kegiatanRhk = @$listKegiatanRhkAtasanSisa[$id_kegiatan_rhk_atasan][$i]; ?>
            <td><?= @$kegiatanRhk->nama ?></td>
        <?php } ?>
    </tr>
<?php } ?>