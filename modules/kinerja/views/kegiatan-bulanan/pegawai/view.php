<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\Helper;
use app\widgets\LabelKegiatan;

/* @var $this yii\web\View */
/* @var $model app\models\KegiatanBulan */

$this->title = "Detail Kegiatan Bulan";
$this->params['breadcrumbs'][] = ['label' => 'Kegiatan Bulan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kegiatan-bulan-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Kegiatan Bulan</h3>
    </div>

    <div class="box-body">
    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            [
                'attribute' => 'bulan',
                'format' => 'raw',
                'value' => $model->getNamaBulan(),
            ],
            [
                'attribute' => 'target_kuantitas',
                'format' => 'raw',
                'value' => $model->target_kuantitas,
            ],
            [
                'label' => 'Progres',
                'value' => $model->getLabelPersenRealisasi()
            ]
        ],
    ]) ?>
    </div>
    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Kegiatan Bulan', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Kegiatan Bulan', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>
</div>

<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Daftar Kegiatan Harian</h3>
    </div>
    <div class="box-body">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th class="text-center" width="3%">No</th>
                    <th>Uraian</th>
                    <th class="text-center">Tanggal</th>
                    <th class="text-center">Kuantitas</th>
                    <th class="text-center">Waktu</th>
                    <th class="text-center">Berkas</th>
                    <th class="text-center">Status</th>
                    <th width="7%">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                <?php foreach ($model->allKegiatanHarian as $kegiatanHari): ?>
                    <tr>
                        <td class="text-center"><?= $i++; ?></td>
                        <td><?= $kegiatanHari->uraian; ?></td>
                        <td class="text-center"><?= Helper::getTanggalSingkat($kegiatanHari->tanggal); ?></td>
                        <td class="text-center"><?= $kegiatanHari->kuantitas; ?></td>
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
