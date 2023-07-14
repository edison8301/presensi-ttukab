<?php

use app\components\Helper;
use app\models\User;
use app\modules\absensi\models\KetidakhadiranJenis;

/* @var $id_ketidakhadiran_jenis int */

?>

<div style="text-align: center; text-transform: uppercase;">Daftar Rekapitulasi Presensi Pegawai</div>

<div>&nbsp;</div>

<table>
<tr>
    <td>OPD</td>
    <td>:</td>
    <td><?=@$searchModel->instansi->nama;?></td>
</tr>
<tr>
    <td>BULAN</td>
    <td>:</td>
    <td style="text-transform:uppercase"><?=Helper::getBulanLengkap($searchModel->bulan) . ' ' . User::getTahun();?></td>
</tr>
</table>

<div>&nbsp;</div>

<table class="table table-bordered" cellpadding="3px" style="font-size:13px">
    <thead>
    <tr>
        <th style="vertical-align: middle; text-align: center; width: 50px">No</th>
        <th style="vertical-align: middle; text-align: center; width: 600px">NAMA PEGAWAI</th>
        <th style="vertical-align: middle; text-align: center; width: 200px">BULAN</th>
        <th style="vertical-align: middle; text-align: center; width: 100px">JUMLAH<BR>HARI<BR>KERJA</th>
        <th style="vertical-align: middle; text-align: center; width: 100px">JUMLAH<BR>HADIR</th>
        <th style="vertical-align: middle; text-align: center; width: 100px">JUMLAH<br>TIDAK<br>HADIR</th>
        <?php
        switch ($id_ketidakhadiran_jenis) {
            case (KetidakhadiranJenis::IZIN):
                $atribut = 'jumlah_izin';
                echo '<th style="vertical-align: middle; text-align: center">JUMLAH IZIN</th>';
                break;
            case (KetidakhadiranJenis::SAKIT):
                $atribut = 'jumlah_sakit';
                echo '<th style="vertical-align: middle; text-align: center">JUMLAH SAKIT</th>';
                break;
            case (KetidakhadiranJenis::CUTI):
                $atribut = 'jumlah_cuti';
                echo '<th style="vertical-align: middle; text-align: center">JUMLAH CUTI</th>';
                break;
            case (KetidakhadiranJenis::DINAS_LUAR):
                $atribut = 'jumlah_dinas_luar';
                echo '<th style="vertical-align: middle; text-align: center">JUMLAH DINAS LUAR</th>';
                break;
            case (KetidakhadiranJenis::TUGAS_BELAJAR):
                $atribut = 'jumlah_tugas_belajar';
                echo '<th style="vertical-align: middle; text-align: center">JUMLAH TUGAS BELAJAR</th>';
                break;
            case (KetidakhadiranJenis::TUGAS_KEDINASAN):
                $atribut = 'jumlah_tugas_kedinasan';
                echo '<th style="vertical-align: middle; text-align: center">JUMLAH TUGAS KEDINASAN</th>';
                break;
            case (KetidakhadiranJenis::ALASAN_TEKNIS):
                $atribut = 'jumlah_alasan_teknis';
                echo '<th style="vertical-align: middle; text-align: center">JUMLAH ALASAN TEKNIS</th>';
                break;
            case (KetidakhadiranJenis::TANPA_KETERANGAN):
                $atribut = 'jumlah_tanpa_keterangan';
                echo '<th style="vertical-align: middle; text-align: center">JUMLAH TANPA KETERANGAN</th>';
                break;
        }
        ?>
    </tr>
    </thead>

<?php $i = 1;foreach ($query->all() as $data) {?>
<?php $pegawai = $data->pegawai?>
<tr>
    <td style="text-align: center"><?=$i++;?></td>
    <td style="text-align: left;"><?=@$data->pegawai->nama;?></td>
    <td style="text-align: center"><?=Helper::getBulanSingkat($data->bulan) . ' ' . $data->tahun;?></td>
    <td style="text-align: center"><?=$data->jumlah_hari_kerja;?></td>
    <td style="text-align: center"><?=$data->jumlah_hadir;?></td>
    <td style="text-align: center"><?=$data->jumlah_tidak_hadir;?></td>
    <td style="text-align: center"><?=$data->{$atribut};?></td>
</tr>
<?php } ?>
</table>
