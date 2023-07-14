<?php

/* @var $searchModel app\models\InstansiPegawaiSearch */

use app\components\Helper;
use app\models\Instansi;

?>
<div style="text-align: center; text-transform: uppercase;">Daftar Rekapitulasi SKP Dan RKB</div>

<div>&nbsp;</div>

<table>
<tr>
    <td>OPD</td>
    <td>:</td>
    <td><?= @$searchModel->instansi->nama; ?></td>
</tr>
<tr>
    <td>BULAN</td>
    <td>:</td>
    <td style="text-transform:uppercase"><?= $searchModel->getBulanLengkapTahun(); ?></td>
</tr>
</table>

<div>&nbsp;</div>

<table class="table table-bordered rekap">
    <thead>
    <tr>
        <th style="text-align: center; vertical-align: middle;" rowspan="2">NO</th>
        <th style="text-align: center; vertical-align: middle;" rowspan="2">NAMA<br>PEGAWAI</th>
        <th style="text-align: center; vertical-align: middle;" rowspan="2">NIP</th>
        <th style="text-align: center; vertical-align: middle;" rowspan="2">JABATAN</th>
        <th style="text-align: center; vertical-align: middle;" colspan="4">PERHITUNGAN SKP DAN RKB</th>
        <th style="text-align: center; vertical-align: middle;" rowspan="2">TOT.<br>POT%</th>
        <th style="text-align: center; vertical-align: middle;" rowspan="2">KET</th>
    </tr>
    <tr>
        <th style="text-align: center; vertical-align: middle;">SKP</th>
        <th style="text-align: center; vertical-align: middle;">POTONGAN<br>SKP<br>(%)</th>
        <th style="text-align: center; vertical-align: middle;">REALISASI<br/>SKB<br/>(%)</th>
        <th style="text-align: center; vertical-align: middle;">POTONGAN<br>RKB<br>(%)</th>
    </tr>
    </thead>
    <?php if ($searchModel->id_instansi!=null) { ?>
        <?php $no = 1; foreach ($searchModel->getAll() as $data) { ?>
            <?= $this->render('_tr-rekap',[
                'searchModel' => $searchModel,
                'data' => $data,
                'no' => $no,
            ]); ?>
        <?php $no++; } ?>
    <?php } else { ?>
        <tr>
            <td colspan="9" style="font-style: italic;">
                Silahkan Pilih Instansi Terlebih dahulu
            </td>
        </tr>
    <?php } ?>
</table>

<?php
/** @var Jabatan $jabatanKepala */
$instansi = Instansi::findOne($searchModel->id_instansi);
$jabatanKepala = @$instansi->getManyJabatanKepala()->one();
$nama = null;
$nip = null;
if ($jabatanKepala) {
    /** @var Pegawai $instansiPegawaiKepala */
    $instansiPegawaiKepala = $jabatanKepala->getManyInstansiPegawai()
        ->orderBy(['tanggal_mulai'=>SORT_DESC])
        ->one();

    if ($instansiPegawaiKepala) {
        $nama = @$instansiPegawaiKepala->pegawai->nama;
        $nip = @$instansiPegawaiKepala->pegawai->nip;
    }
}
?>

<?php if($tandatangan == true) { ?>
    <?= $this->render('_tandatangan-elektronik', [
        'nama' => $nama,
        'jabatan' => @$jabatanKepala->nama,
        'nip' => $nip,
    ]); ?>
<?php } ?>
