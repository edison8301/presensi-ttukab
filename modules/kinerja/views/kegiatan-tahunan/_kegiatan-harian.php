<?php

use app\components\Helper;
use yii\helpers\Html;
use app\widgets\LabelKegiatan;
use app\widgets\Label;
?>
<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title">Daftar Realisasi Harian</h3>
    </div>
    <div class="box-header">
        <?= Html::a(
            '<i class="fa fa-plus"></i> Tambah Realisasi Harian',
            [
                'kegiatan-harian/create',
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
                    <th class="text-center" width="3%">No</th>
                    <th class="text-center" width="100px">Tanggal</th>
                    <th>Uraian</th>
                    <th style="width:100px" class="text-center">Kuantitas</th>
                    <th style="width:150px" class="text-center">Waktu</th>
                    <th style="width:100px" class="text-center">Berkas</th>
                    <th style="width:100px" class="text-center">Status</th>
                    <th width="7%">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                <?php foreach ($model->allKegiatanHarian as $kegiatanHari): ?>
                    <tr>
                        <td class="text-center"><?= $i++; ?></td>
                        <td class="text-center"><?= Helper::getTanggalSingkat($kegiatanHari->tanggal); ?></td>
                        <td><?= $kegiatanHari->uraian; ?></td>
                        <td class="text-center"><?= $kegiatanHari->getKuantitasSatuan(); ?></td>
                        <td class="text-center"><?= $kegiatanHari->getWaktu(); ?></td>
                        <td class="text-center"><?= $kegiatanHari->berkas; ?></td>
                        <td class="text-center"><?= LabelKegiatan::widget(['kegiatan' => $kegiatanHari]); ?></td>
                        <td class="text-center">
                            <?= Html::a('<i class="fa fa-eye"></i>', ['kegiatan-harian/view', 'id' => $kegiatanHari->id]); ?>
                            <?= Html::a('<i class="fa fa-pencil"></i>', ['kegiatan-harian/update', 'id' => $kegiatanHari->id]); ?>
                            <?= Html::a('<i class="fa fa-trash"></i>', ['kegiatan-harian/delete', 'id' => $kegiatanHari->id], [
                                'data' => [
                                    'confirm' => 'Yakin Akan Menghapus Data?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>
