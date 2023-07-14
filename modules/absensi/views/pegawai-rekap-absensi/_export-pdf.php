<?php

use app\models\User;
use app\components\Helper;
?>

<div style="text-align: center; text-transform: uppercase;">Daftar Rekapitulasi Presensi Pegawai</div>

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
    <td style="text-transform:uppercase"><?= Helper::getBulanLengkap($searchModel->bulan).' '.User::getTahun(); ?></td>
</tr>
</table>

<div>&nbsp;</div>

<table class="table table-bordered" cellpadding="3px" style="font-size:11px">
    <thead>
    <tr>
        <th style="vertical-align: middle; text-align: center; width:30px">No</th>
        <th style="vertical-align: middle; text-align: center" width="">NAMA PEGAWAI</th>
        <th style="vertical-align: middle; text-align: center">BULAN</th>
        <th style="vertical-align: middle; text-align: center">JUMLAH<BR>HARI<BR>KERJA</th>
        <th style="vertical-align: middle; text-align: center">JUMLAH<BR>KEHADIRAN</th>
        <th style="vertical-align: middle; text-align: center">JUMLAH<br>TIDAK<br> HADIR</th>
        <th style="vertical-align: middle; text-align: center">POT<br>FP %</th>
        <th style="vertical-align: middle; text-align: center">POT<br>KEG %</th>
        <th style="vertical-align: middle; text-align: center">POT<br>TTL %</th>
    </tr>
    </thead>
<?php /*
<tr>
    <?php for($i=1; $i<=20; $i++) { ?>
    <td style="text-align: center"><?= $i; ?></td>
    <?php } ?>
</tr>
*/ ?>

<?php $i=1; foreach($query->all() as $pegawaiRekap) { ?>
<?php $pegawai = $pegawaiRekap->pegawai ?>
<tr>
    <td style="text-align:center"><?= $i; ?></td>
    <td>
        <?= $pegawai->nama; ?><br>
        <?= 'NIP.'.$pegawai->nip; ?>
    </td>
    <td style="text-align: center"><?= Helper::getBulanSingkat($searchModel->bulan).'<br>'.$searchModel->tahun; ?></td>
    <td style="text-align: center"><?= $pegawaiRekap->jumlah_hari_kerja; ?></td>
    <td style="text-align: center"><?= $pegawaiRekap->jumlah_hadir; ?></td>
    <td style="text-align: center"><?= $pegawaiRekap->jumlah_tidak_hadir; ?></td>
    <td style="text-align: center"><?= $pegawaiRekap->persen_potongan_fingerprint; ?></td>
    <td style="text-align: center"><?= $pegawaiRekap->persen_potongan_kegiatan; ?></td>
    <td style="text-align: center"><?= $pegawaiRekap->persen_potongan_total; ?></td>
</tr>
<?php $i++; } ?>
</table>
