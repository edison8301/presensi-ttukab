<?php

use app\components\Helper;
use yii\helpers\Html;

/* @var $model app\models\Pegawai */

?>

<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title">Golongan Pegawai</h3>
    </div>
    <div class="box-header">
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Data / Golongan', ['pegawai-golongan/create','id_pegawai' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th style="vertical-align: middle; text-align: center; width: 7px;">No</th>
                <th style="vertical-align: middle; text-align: center">Golongan</th>
                <th style="vertical-align: middle; text-align: center; width: 150px">Tanggal TMT</th>
                <th style="vertical-align: middle; text-align: center; width: 150px">Tanggal<br/>Mulai Efektif</th>
                <th style="vertical-align: middle; text-align: center; width: 150px">Tanggal<br/>Selesai Efektif</th>
                <th style="vertical-align: middle; width: 80px">&nbsp;</th>
            </tr>
            </thead>
            <?php $i = 1; ?>
            <?php foreach ($model->getAllPegawaiGolongan() as $pegawaiGolongan) { ?>
                <tr>
                    <td style="text-align: center"><?= $i++; ?></td>
                    <td style="text-align: center">
                        <?= @$pegawaiGolongan->golongan->golongan; ?>
                    </td>
                    <td style="text-align: center">
                        <?= Helper::getTanggalSingkat($pegawaiGolongan->tanggal_berlaku) ?>
                    </td>
                    <td style="text-align: center">
                        <?= Helper::getTanggalSingkat($pegawaiGolongan->tanggal_mulai) ?>
                    </td>
                    <td style="text-align: center">
                        <?= $pegawaiGolongan->getLabelTanggalSelesai() ?>
                    </td>
                    <td style="text-align: center">
                        <?= $pegawaiGolongan->getLinkIconUpdate(); ?>
                        <?= $pegawaiGolongan->getLinkIconDelete(); ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>
