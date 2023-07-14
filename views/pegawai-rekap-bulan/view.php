<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PegawaiRekapBulan */

$this->title = "Detail Pegawai Rekap Bulan";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Rekap Bulan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-rekap-bulan-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Pegawai Rekap Bulan</h3>
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
                'attribute' => 'id_pegawai',
                'format' => 'raw',
                'value' => $model->id_pegawai,
            ],
            [
                'attribute' => 'bulan',
                'format' => 'raw',
                'value' => $model->bulan,
            ],
            [
                'attribute' => 'tahun',
                'format' => 'raw',
                'value' => $model->tahun,
            ],
            [
                'attribute' => 'id_pegawai_rekap_jenis',
                'format' => 'raw',
                'value' => $model->id_pegawai_rekap_jenis,
            ],
            [
                'attribute' => 'nilai',
                'format' => 'raw',
                'value' => $model->nilai,
            ],
            [
                'attribute' => 'status_kunci',
                'format' => 'raw',
                'value' => $model->status_kunci,
            ],
            [
                'attribute' => 'waktu_kunci',
                'format' => 'raw',
                'value' => $model->waktu_kunci,
            ],
            [
                'attribute' => 'waktu_buat',
                'format' => 'raw',
                'value' => $model->waktu_buat,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Pegawai Rekap Bulan', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Pegawai Rekap Bulan', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
