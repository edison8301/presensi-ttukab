<?php

use app\modules\absensi\models\Absensi;

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
    <td style="text-transform:uppercase"><?= $searchModel->getBulanLengkapTahun(); ?></td>
</tr>
</table>

<div>&nbsp;</div>

<table class="table table-bordered" cellpadding="3px" style="font-size:11px">
<tr>
    <th style="vertical-align: middle; text-align: center; width:30px" rowspan="2">No</th>
    <th style="vertical-align: middle; text-align: center" rowspan="2">NAMA</th>
    <th style="vertical-align: middle; text-align: center" rowspan="2">GOL</th>
    <th style="vertical-align: middle; text-align: center" rowspan="2">JUMLAH<BR>HARI<BR>KERJA</th>
    <th style="vertical-align: middle; text-align: center" rowspan="2">JUMLAH<BR>KEHADIRAN</th>
    <th style="vertical-align: middle; text-align: center" colspan="7" ="2">JUMLAH TIDAK HADIR</th>
    <th style="vertical-align: middle; text-align: center" rowspan="2">POT<br>FP %</th>
    <th style="vertical-align: middle; text-align: center" colspan="2">APEL</th>
    <th style="vertical-align: middle; text-align: center" rowspan="2">UP</th>
    <th style="vertical-align: middle; text-align: center" rowspan="2">OR</th>
    <th style="vertical-align: middle; text-align: center" rowspan="2">SIDAK</th>
    <th style="vertical-align: middle; text-align: center" rowspan="2">POT<br>KEG %</th>
    <th style="vertical-align: middle; text-align: center" rowspan="2">POT<br>TOT %</th>
    <th style="vertical-align: middle; text-align: center" rowspan="2">KET</th>
</tr>
<tr>
    <th style="vertical-align: middle; text-align: center">I</th>
    <th style="vertical-align: middle; text-align: center">S</th>
    <th style="vertical-align: middle; text-align: center">C</th>
    <th style="vertical-align: middle; text-align: center">DL</th>
    <th style="vertical-align: middle; text-align: center">TB</th>
    <th style="vertical-align: middle; text-align: center">TD</th>
    <th style="vertical-align: middle; text-align: center">TK</th>
    <th style="vertical-align: middle; text-align: center">P</th>
    <th style="vertical-align: middle; text-align: center">S</th>
</tr>
<?php /*
<tr>
    <?php for($i=1; $i<=20; $i++) { ?>
    <td style="text-align: center"><?= $i; ?></td>
    <?php } ?>
</tr>
*/ ?>

<?php $i=1; foreach($query->all() as $pegawai) { ?>
<?php $pegawaiRekapAbsensi = $pegawai->findPegawaiRekapAbsensi($searchModel->bulan); ?>
<tr>
    <td style="text-align:center"><?= $i; ?></td>
    <td>
        <?= $pegawai->nama; ?><br>
        <?= 'NIP.'.$pegawai->nip; ?>
    </td>
    <td style="text-align: center"><?= @$pegawai->golongan; ?></td>
    <td style="text-align: center"><?php print $pegawaiRekapAbsensi->_hari_kerja; ?></td>
    <td style="text-align: center"><?php print $pegawaiRekapAbsensi->_hari_hadir; ?></td>
    <td style="text-align: center"><?php print $pegawaiRekapAbsensi->_hari_ketidakhadiran[Absensi::KETIDAKHADIRAN_IZIN]; ?></td>
    <td style="text-align: center"><?php print $pegawaiRekapAbsensi->_hari_ketidakhadiran[Absensi::KETIDAKHADIRAN_SAKIT]; ?></td>
    <td style="text-align: center"><?php print $pegawaiRekapAbsensi->_hari_ketidakhadiran[Absensi::KETIDAKHADIRAN_CUTI]; ?></td>
    <td style="text-align: center"><?php print $pegawaiRekapAbsensi->_hari_ketidakhadiran[Absensi::KETIDAKHADIRAN_DINAS_LUAR]; ?></td>
    <td style="text-align: center"><?php print $pegawaiRekapAbsensi->_hari_ketidakhadiran[Absensi::KETIDAKHADIRAN_TUGAS_BELAJAR]; ?></td>
    <td style="text-align: center"><?php print $pegawaiRekapAbsensi->_hari_ketidakhadiran[Absensi::KETIDAKHADIRAN_TUGAS_KEDINASAN]; ?></td>
    <td style="text-align: center"><?php print $pegawaiRekapAbsensi->_hari_tanpa_keterangan; ?></td>
    <td style="text-align: center"><?php print $pegawaiRekapAbsensi->_potongan_bulan_fingerprint; ?></td>
    <td style="text-align: center"><?php print $pegawaiRekapAbsensi->_hari_tanpa_keterangan; ?></td>
    <td style="text-align: center"><?php print $pegawaiRekapAbsensi->_hari_tanpa_keterangan; ?></td>
    <td style="text-align: center"><?php print $pegawaiRekapAbsensi->_hari_tanpa_keterangan; ?></td>
    <td style="text-align: center"><?php print $pegawaiRekapAbsensi->_hari_tanpa_keterangan; ?></td>
    <td style="text-align: center"><?php print $pegawaiRekapAbsensi->_hari_tanpa_keterangan; ?></td>
    <td style="text-align: center"><?php print $pegawaiRekapAbsensi->_potongan_bulan_kegiatan; ?></td>
    <td style="text-align: center"><?php print $pegawaiRekapAbsensi->_potongan_bulan; ?></td>
    <td style="text-align: center">&nbsp;</td>
</tr>
<?php $i++; } ?>
</table>
