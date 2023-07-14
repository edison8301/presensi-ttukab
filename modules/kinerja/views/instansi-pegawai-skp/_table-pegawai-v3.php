<?php

/* @var $model \app\modules\kinerja\models\InstansiPegawaiSkp */

?>

<table class="table table-bordered">
    <tr>
        <th style="text-align: center;">NO</th>
        <th style="text-align: center;" colspan="2">PEGAWAI YANG DINILAI</th>
        <th style="text-align: center;">NO</th>
        <th style="text-align: center;" colspan="2">PEJABAT PENILAI KINERJA</th>
    </tr>
    <tr>
        <td style="text-align: center; width: 3%">1</td>
        <td>NAMA</td>
        <td style="width: 380px">
            <?= @$model->pegawai->nama ?>
        </td>
        <td style="text-align: center; width: 3%">1</td>
        <td>NAMA</td>
        <td style="width: 380px">
            <?php if ($model->isJpt() === false) { ?>
                <?= @$model->instansiPegawai->atasan->nama ?>
            <?php } ?>

            <?php if ($model->isJpt() === true) { ?>
                Dr. Ir. RIDWAN DJAMALUDDIN, M.Sc
            <?php } ?>
        </td>
    </tr>
    <tr>
        <td style="text-align: center;">2</td>
        <td>NIP</td>
        <td>
            <?= @$model->pegawai->nip ?>
        </td>
        <td style="text-align: center;">2</td>
        <td>NIP</td>
        <td>
            <?php if ($model->isJpt() === false) { ?>
                <?= @$model->instansiPegawai->atasan->nip ?>
            <?php } ?>

            <?php if ($model->isJpt() === true) { ?>
                -
            <?php } ?>
        </td>
    </tr>
    <tr>
        <td style="text-align: center;">3</td>
        <td>PANGKAT/GOL RUANG</td>
        <td>
            <?= @$model->pegawai->golongan->getPangkatKomaGolongan() ?>
        </td>
        <td style="text-align: center;">3</td>
        <td>PANGKAT/GOL RUANG</td>
        <td>
            <?php if ($model->isJpt() === false) { ?>
                <?= @$model->instansiPegawai->atasan->golongan->getPangkatKomaGolongan() ?>
            <?php } ?>

            <?php if ($model->isJpt() === true) { ?>
                -
            <?php } ?>
        </td>
    </tr>
    <tr>
        <td style="text-align: center;">4</td>
        <td>JABATAN</td>
        <td>
            <?= $model->instansiPegawai->namaJabatan ?>
        </td>
        <td style="text-align: center;">4</td>
        <td>JABATAN</td>
        <td>
            <?php if ($model->isJpt() === false) { ?>
                <?= @$model->instansiPegawai->jabatanAtasan->namaJabatan ?>
            <?php } ?>

            <?php if ($model->isJpt() === true) { ?>
                Pj. GUBERNUR
            <?php } ?>
        </td>
    </tr>
    <tr>
        <td style="text-align: center;">5</td>
        <td>UNIT KERJA</td>
        <td>
            <?= strtoupper(@$model->instansiPegawai->instansi->nama) ?>
        </td>
        <td style="text-align: center;">5</td>
        <td>UNIT KERJA</td>
        <td>
            <?php if ($model->isJpt() === false) { ?>
                <?= strtoupper(@$model->instansiPegawai->jabatanAtasan->instansi->nama) ?>
            <?php } ?>

            <?php if ($model->isJpt() === true) { ?>
                KABUPATEN TIMOR TENGAH UTARA
            <?php } ?>
        </td>
    </tr>
</table>
