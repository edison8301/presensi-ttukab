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

<table class="table table-bordered" cellpadding="3px" style="font-size:11px; margin-bottom: 200px;">
    <thead>
        <tr>
            <th style="vertical-align: middle; text-align: center; width:30px; vertical-align: bottom; border-bottom: 0px">No</th>
            <th style="vertical-align: middle; text-align: center; vertical-align: bottom; border-bottom: 0px">NAMA</th>
            <th style="vertical-align: middle; text-align: center; vertical-align: bottom; border-bottom: 0px">GOL</th>
            <th style="vertical-align: middle; text-align: center; vertical-align: bottom; border-bottom: 0px">JUMLAH<br>HARI<br>KERJA</th>
            <th style="vertical-align: middle; text-align: center; vertical-align: bottom; border-bottom: 0px">JUMLAH<br>KEHADIRAN</th>
            <th style="vertical-align: middle; text-align: center" colspan="5">JUMLAH TIDAK HADIR</th>
            <th style="vertical-align: middle; text-align: center; vertical-align: bottom; border-bottom: 0px">POT<br>FP %</th>
            <th style="vertical-align: middle; text-align: center" colspan="2">APEL</th>
            <th style="vertical-align: middle; text-align: center; border-bottom: 0px; vertical-align: bottom;">UP</th>
            <th style="vertical-align: middle; text-align: center; border-bottom: 0px; vertical-align: bottom;">OR</th>
            <th style="vertical-align: middle; text-align: center; border-bottom: 0px; vertical-align: bottom;">SIDAK</th>
            <th style="vertical-align: middle; text-align: center; border-bottom: 0px; vertical-align: bottom;">POT<br>KEG %</th>
            <th style="vertical-align: middle; text-align: center; border-bottom: 0px; vertical-align: bottom;">POT<br>TOT %</th>
            <th style="vertical-align: middle; text-align: center; border-bottom: 0px; vertical-align: bottom;">KET</th>
        </tr>
        <tr>
            <th style="border-top: 0px;"></th>
            <th style="border-top: 0px;"></th>
            <th style="border-top: 0px;"></th>
            <th style="border-top: 0px;"></th>
            <th style="border-top: 0px;"></th>
            <th style="vertical-align: middle; text-align: center">C</th>
            <th style="vertical-align: middle; text-align: center">DL</th>
            <th style="vertical-align: middle; text-align: center">TD</th>
            <th style="vertical-align: middle; text-align: center">DKLT</th>
            <th style="vertical-align: middle; text-align: center">TK</th>
            <th style="border-top: 0px;"></th>
            <th style="vertical-align: middle; text-align: center">P</th>
            <th style="vertical-align: middle; text-align: center">S</th>
            <th style="border-top: 0px;"></th>
            <th style="border-top: 0px;"></th>
            <th style="border-top: 0px;"></th>
            <th style="border-top: 0px;"></th>
            <th style="border-top: 0px;"></th>
            <th style="border-top: 0px;"></th>
        </tr>
    </thead>
<?php /*
<tr>
    <?php for($i=1; $i<=20; $i++) { ?>
    <td style="text-align: center"><?= $i; ?></td>
    <?php } ?>
</tr>
*/ ?>

<?php $i=1; foreach($query->all() as $pegawaiRekapAbsensi) { ?>
<tr>
    <td style="text-align:center"><?= $i; ?></td>
    <td>
        <?= @$pegawaiRekapAbsensi->pegawai->nama; ?><br>
        <?= 'NIP.'.@$pegawaiRekapAbsensi->pegawai->nip; ?>
    </td>
    <td style="text-align: center"><?= @$pegawaiRekapAbsensi->pegawai->golongan; ?></td>
    <td style="text-align: center"><?php print $pegawaiRekapAbsensi->jumlah_hari_kerja; ?></td>
    <td style="text-align: center"><?php print $pegawaiRekapAbsensi->getJumlahHadir(); ?></td>
    <td style="text-align: center"><?php print $pegawaiRekapAbsensi->getJumlahCuti(); ?></td>
    <td style="text-align: center"><?php print $pegawaiRekapAbsensi->jumlah_dinas_luar; ?></td>
    <td style="text-align: center"><?php print $pegawaiRekapAbsensi->jumlah_tugas_kedinasan; ?></td>
    <td style="text-align: center"><?php print $pegawaiRekapAbsensi->jumlah_diklat; ?></td>
    <td style="text-align: center"><?php print $pegawaiRekapAbsensi->jumlah_ketidakhadiran_jam_tanpa_keterangan; ?></td>
    <td style="text-align: center"><?php print $pegawaiRekapAbsensi->persen_potongan_fingerprint; ?></td>
    <td style="text-align: center"><?php print $pegawaiRekapAbsensi->jumlah_tidak_hadir_apel_pagi; ?></td>
    <td style="text-align: center"><?php print $pegawaiRekapAbsensi->jumlah_tidak_hadir_apel_sore; ?></td>
    <td style="text-align: center"><?php print $pegawaiRekapAbsensi->jumlah_tidak_hadir_upacara; ?></td>
    <td style="text-align: center"><?php print $pegawaiRekapAbsensi->jumlah_tidak_hadir_senam; ?></td>
    <td style="text-align: center"><?php print $pegawaiRekapAbsensi->jumlah_tidak_hadir_sidak; ?></td>
    <td style="text-align: center"><?php print $pegawaiRekapAbsensi->persen_potongan_kegiatan; ?></td>
    <td style="text-align: center"><?php print $pegawaiRekapAbsensi->persen_potongan_total; ?></td>
    <td style="text-align: center">&nbsp;</td>
</tr>
<?php $i++; } ?>
</table>
