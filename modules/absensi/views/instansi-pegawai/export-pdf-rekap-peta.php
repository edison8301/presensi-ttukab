<?php 

use app\components\Session;

/* @var $this yii\web\View */
/* @var $pegawaiPetaAbsensiForm app\models\PegawaiPetaAbsensiForm; */
/* @var $allPegawaiPetaAbsensiReport app\models\PegawaiPetaAbsensiReport[]; */

?>

<div style="text-align: center;">DAFTAR REKAPITULASI ABSENSI PEGAWAI</div>
<div style="text-align: center;">TAHUN <?= Session::getTahun() ?></div>

<div>&nbsp;</div>

<table>
    <tr>
        <td style="width: 120px;">PD / UNIT KERJA</td>
        <td style="width: 20px;">:</td>
        <td><?= @$pegawaiPetaAbsensiForm->instansi->nama; ?></td>
    </tr>
    <tr>
        <td>BULAN</td>
        <td>:</td>
        <td style="text-transform:uppercase"><?= $pegawaiPetaAbsensiForm->getBulanLengkapTahun(); ?></td>
    </tr>
    <tr>
        <td>Lokasi</td>
        <td>:</td>
        <td style="text-transform:uppercase"><?= @$pegawaiPetaAbsensiForm->peta->nama; ?></td>
    </tr>
</table>

<div>&nbsp;</div>

<?= $this->render('_table-rekap-peta', [
    'pegawaiPetaAbsensiForm' => $pegawaiPetaAbsensiForm,
    'allPegawaiPetaAbsensiReport' => $allPegawaiPetaAbsensiReport,
    'pdf' => true,
]) ?>