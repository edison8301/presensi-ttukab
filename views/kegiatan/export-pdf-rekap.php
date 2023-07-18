<?php

use app\components\Helper;

?>

<h5 class="text-center">
    DATA KEHADIRAN
</h5>

<br/>
<table>
    <tr>
        <td style="width: 150px;">Perangkat Daerah</td>
        <td style="width: 5px;">:</td>
        <td>
            <?= @$instansi->nama ?>
        </td>
    </tr>
    <tr>
        <td>Kegiatan</td>
        <td>:</td>
        <td>
            <?= $model->nama ?>
        </td>
    </tr>
    <tr>
        <td>Tanggal</td>
        <td>:</td>
        <td>
            <?= Helper::getTanggal($model->tanggal) ?>
        </td>
    </tr>
</table>

<br/>
<table class="table" border="1">
    <tr>
        <th style="text-align: center; width: 50px;">NO</th>
        <th>NAMA</th>
        <th style="width: 180px;">NIP</th>
        <th style="width: 100px;">STATUS</th>
    </tr>
    <?php $no=1; foreach ($allInstansiPegawai as $instansiPegawai) { ?>
        <tr>
            <td style="text-align: center;">
                <?= $no++; ?>
            </td>
            <td>
                <?= @$instansiPegawai->pegawai->nama ?>
            </td>
            <td style="text-align: center;">
                <?= @$instansiPegawai->pegawai->nip ?>
            </td>
            <td style="text-align: center;">
                <?= $model->getStatusHadirPegawai([
                    'id_pegawai' => $instansiPegawai->id_pegawai,
                ]) ?>
            </td>
        </tr>
    <?php } ?>
</table>