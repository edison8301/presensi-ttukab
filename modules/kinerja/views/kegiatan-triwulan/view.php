<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\KegiatanTriwulan */

$this->title = "Detail Kegiatan Triwulan";
$this->params['breadcrumbs'][] = ['label' => 'Kegiatan Triwulan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kegiatan-triwulan-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Kegiatan Triwulan</h3>
    </div>

    <div class="box-body">

    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            [
                'attribute' => 'id',
                'format' => 'raw',
                'value' => $model->id,
            ],
            [
                'attribute' => 'id_kegiatan_tahunan',
                'format' => 'raw',
                'value' => $model->id_kegiatan_tahunan,
            ],
            // [
            //     'attribute' => 'id_kegiatan_bulanan',
            //     'format' => 'raw',
            //     'value' => $model->id_kegiatan_bulanan,
            // ],
            [
                'attribute' => 'tahun',
                'format' => 'raw',
                'value' => $model->tahun,
            ],
            [
                'attribute' => 'periode',
                'format' => 'raw',
                'value' => $model->periode,
            ],
            [
                'attribute' => 'target',
                'format' => 'raw',
                'value' => $model->target,
            ],
            [
                'attribute' => 'realisasi',
                'format' => 'raw',
                'value' => $model->realisasi,
            ],
            [
                'attribute' => 'persen_capaian',
                'format' => 'raw',
                'value' => $model->persen_capaian,
            ],
            [
                'attribute' => 'deskripsi_capaian',
                'format' => 'raw',
                'value' => $model->deskripsi_capaian,
            ],
            [
                'attribute' => 'kendala',
                'format' => 'raw',
                'value' => $model->kendala,
            ],
            [
                'attribute' => 'tindak_lanjut',
                'format' => 'raw',
                'value' => $model->tindak_lanjut,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Kegiatan Triwulan', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Kegiatan Triwulan', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
