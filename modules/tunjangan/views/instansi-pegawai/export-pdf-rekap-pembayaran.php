<?php

/* @var $searchModel \app\models\InstansiPegawaiSearch */
/* @var $allInstansiPegawai \app\models\InstansiPegawai[] */

?>

<div style="text-align: center;">DAFTAR NOMINATIF PEMBAYARAN TAMBAHAN PENGHASILAN PEGAWAI ASN</div>
<div style="text-align: center;">KABUPATEN TIMOR TENGAH UTARA</div>

<div>&nbsp;</div>

<table>
    <tr>
        <td style="width: 100px;">BULAN</td>
        <td style="width: 10px;">:</td>
        <td style="text-transform:uppercase"><?= $searchModel->getBulanLengkapTahun(); ?></td>
    </tr>
</table>

<div>&nbsp;</div>

<table cellpadding="6" border="1" style="background: #ffffff; margin-bottom:20px">
    <thead>
        <tr>
            <th style="width: 10px;">NO</th>
            <th>PD / UNIT KERJA</th>
            <th>NIP</th>
            <th>NAMA</th>
            <th>GOL</th>
            <th>JABATAN</th>
            <th>KELAS JABATAN</th>
            <th>BESARAN TPP</th>
            <th>KETERANGAN</th>
        </tr>
    </thead>
    <?php $no=1; foreach ($allInstansiPegawai as $instansiPegawai) { ?>
        <tr>
            <td style="text-align: center;"><?= $no++ ?></td>
            <td>
                <?= @$instansiPegawai->instansi->nama ?>
            </td>
            <td style="text-align: center;">
                <?= $instansiPegawai->pegawai->nip ?>
            </td>
            <td>
                <?= $instansiPegawai->pegawai->nama ?>
            </td>
            <td style="text-align: center">
                <?= @$instansiPegawai->pegawai->getNamaPegawaiGolonganBerlaku([
                    'bulan'=>$searchModel->bulan,
                ]); ?>
            </td>
            <td>
                <?= $instansiPegawai->nama_jabatan ?>
            </td>
            <td style="text-align: center;">
                <?= $instansiPegawai->jabatan->kelas_jabatan ?>
            </td>
            <td>

            </td>
            <td>

            </td>
        </tr>
    <?php } ?>
</table>
