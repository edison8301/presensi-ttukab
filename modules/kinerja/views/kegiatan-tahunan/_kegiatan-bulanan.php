<?php

use app\components\Helper;
use yii\helpers\Html;
?>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Daftar Target Bulanan</h3>
    </div>
    <div class="box-header">
        <?= Html::a(
            '<i class="fa fa-plus"></i> Tambah Target Bulanan',
            [
                'kegiatan-bulanan/create',
                'id_kegiatan_tahunan' => $model->id
            ],
            [
                'class' => 'btn btn-success btn-flat'
            ]
        ); ?>
    </div>
    <div class="box-body">
        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th class="text-center" width="60px">No</th>
                    <th>Bulan</th>
                    <th style="text-align:center" width="250px">Target Kuantitas</th>
                    <th style="text-align: center;" width="120px">Realisasi</th>
                    <th width="80px">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                <?php foreach ($model->allKegiatanBulanan as $kegiatanBulan): ?>
                    <tr>
                        <td style="text-align: center"><?= $i++; ?></td>
                        <td><?= $kegiatanBulan->getNamaBulan(); ?></td>
                        <td style="text-align: center"><?= $kegiatanBulan->getTargetSatuan(); ?></td>
                        <td style="text-align: center;">
                            <span class="label label-info"><?= $kegiatanBulan->getLabelPersenRealisasi(); ?></span></td>
                        <td style="text-align: center">
                            <?= Html::a('<i class="fa fa-eye"></i>', ['kegiatan-bulanan/view', 'id' => $kegiatanBulan->id]); ?>
                            <?= Html::a('<i class="fa fa-pencil"></i>', ['kegiatan-bulanan/update', 'id' => $kegiatanBulan->id]); ?>
                            <?= Html::a('<i class="fa fa-trash"></i>', ['kegiatan-bulanan/delete', 'id' => $kegiatanBulan->id], [
                                'data' => [
                                    'confirm' => 'Yakin Akan Menghapus Data?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right;font-weight: bold;">Rata-rata</td>
                    <td style="text-align: center;"><?= $model->getAveragePersenRealisasiPerBulan(); ?></td>
                    <td>&nbsp;</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
