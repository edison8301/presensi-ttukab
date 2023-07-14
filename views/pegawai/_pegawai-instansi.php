<?php

use app\components\Helper;

?>

<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title">Unit Kerja dan Jabatan Pegawai</h3>
    </div>
    <div class="box-header">
        <?= $this->render('_modal-instansi',['pegawai'=>$model]); ?>
    </div>
    <div class="box-body">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th style="text-align: center; width: 7px;">No</th>
                <th style="text-align: center">Instansi</th>
                <th style="text-align: center">Jabatan</th>
                <th style="text-align: center">Atasan</th>
                <th style="text-align: center; width: 100px">Tanggal TMT</th>
                <th style="text-align: center; width: 100px">Tanggal<br/>Mulai Efektif</th>
                <th style="text-align: center; width: 100px">Tanggal<br/>Selesai Efektif</th>
                <th style="width: 80px">&nbsp;</th>
            </tr>
            </thead>
            <?php $i = 1; ?>
            <?php foreach ($model->allInstansiPegawai as $instansiPegawai) { ?>
                <tr>
                    <td style="text-align: center"><?= $i++; ?></td>
                    <td>
                        <?= $instansiPegawai->nama_instansi ?>
                    </td>
                    <td style="text-align: center">
                        <?= $instansiPegawai->nama_jabatan ?>
                    </td>
                    <td style="text-align: center">
                        <?= $instansiPegawai->nama_jabatan_atasan ?>
                    </td>
                    <td style="text-align: center">
                        <?= Helper::getTanggalSingkat($instansiPegawai->tanggal_berlaku) ?>
                    </td>
                    <td style="text-align: center">
                        <?= Helper::getTanggalSingkat($instansiPegawai->tanggal_mulai) ?>
                    </td>
                    <td style="text-align: center">
                        <?= Helper::getTanggalSingkat($instansiPegawai->tanggal_selesai) ?>
                    </td>
                    <td style="text-align: center">
                        <?= $instansiPegawai->getLinkIconUpdate(); ?>
                        <?= $instansiPegawai->getLinkIconDelete(); ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>
