<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\widgets\LabelKegiatan;

/* @var $this yii\web\View */
/* @var $model app\models\KegiatanTahunan */

$this->title = "Detail Kegiatan Tahunan";
$this->params['breadcrumbs'][] = ['label' => 'Kegiatan Tahunan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kegiatan-tahunan-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Kegiatan Tahunan</h3>
    </div>

    <div class="box-body">

    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            [
                'attribute' => 'id_pegawai',
                'format' => 'raw',
                'value' => $model->pegawai->nama,
            ],
            [
                'attribute' => 'nama_kegiatan',
                'format' => 'raw',
                'value' => $model->nama_kegiatan,
            ],
            [
                'attribute' => 'target_kuantitas',
                'format' => 'raw',
                'value' => $model->target_kuantitas,
            ],
            [
                'attribute' => 'satuan_kuantitas',
                'format' => 'raw',
                'value' => $model->satuan_kuantitas,
            ],
            [
                'attribute' => 'target_waktu',
                'format' => 'raw',
                'value' => $model->getTargetWaktu(),
            ],
            [
                'attribute' => 'kode_kegiatan_status',
                'format' => 'raw',
                'value' => LabelKegiatan::widget(['kegiatan' => $model]),
            ],
            [
                'attribute' => 'id_pegawai_penyetuju',
                'format' => 'raw',
                'value' => $model->getRelationField('pegawaiPenyetuju', 'nama'),
            ],
            [
                'attribute' => 'waktu_dibuat',
                'format' => 'datetime',
                'value' => $model->waktu_dibuat,
            ],
            [
                'attribute' => 'waktu_disetujui',
                'format' => 'datetime',
                'value' => $model->waktu_disetujui,
            ],
        ],
    ]) ?>
    </div>
    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Kegiatan Tahunan', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Kegiatan Tahunan', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>`
</div>

<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Daftar Kegiatan Bulanan</h3>
    </div>
    <div class="box-body">
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
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th class="text-center" width="60px">No</th>
                    <th>Bulan</th>
                    <th style="text-align:center" width="250px">Target Kuantitas</th>
                    <th style="text-align: center;">Progres</th>
                    <th width="80px">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                <?php foreach ($model->allKegiatanBulanan as $kegiatanBulan): ?>
                    <tr>
                        <td style="text-align:center"><?= $i++; ?></td>
                        <td><?= $kegiatanBulan->getNamaBulan(); ?></td>
                        <td style="text-align:center"><?= $kegiatanBulan->target_kuantitas . ' ' . $model->satuan_kuantitas; ?></td>
                        <td style="text-align: center;">
                            <span class="label label-info">
                                <?= $kegiatanBulan->getLabelPersenRealisasi(); ?>
                            </span>
                        </td>
                        <td style="text-align:center">
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
