<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\JamKerjaReguler */

$this->title = "Detail Jam Kerja Reguler";
$this->params['breadcrumbs'][] = ['label' => 'Jam Kerja Reguler', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jam-kerja-reguler-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Jam Kerja Reguler</h3>
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
                'attribute' => 'id_shift_kerja_reguler',
                'format' => 'raw',
                'value' => $model->id_shift_kerja_reguler,
            ],
            [
                'attribute' => 'id_jam_kerja_jenis',
                'format' => 'raw',
                'value' => $model->id_jam_kerja_jenis,
            ],
            [
                'attribute' => 'hari',
                'format' => 'raw',
                'value' => $model->hari,
            ],
            [
                'attribute' => 'nama',
                'format' => 'raw',
                'value' => $model->nama,
            ],
            [
                'attribute' => 'jam_mulai_hitung',
                'format' => 'raw',
                'value' => $model->jam_mulai_hitung,
            ],
            [
                'attribute' => 'jam_selesai_hitung',
                'format' => 'raw',
                'value' => $model->jam_selesai_hitung,
            ],
            [
                'attribute' => 'jam_minimal_absensi',
                'format' => 'raw',
                'value' => $model->jam_minimal_absensi,
            ],
            [
                'attribute' => 'jam_maksimal_absensi',
                'format' => 'raw',
                'value' => $model->jam_maksimal_absensi,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Jam Kerja Reguler', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Jam Kerja Reguler', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
