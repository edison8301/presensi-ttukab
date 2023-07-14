<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Catatan */

$this->title = "Detail Kegiatan Tahunan Catatan";
$this->params['breadcrumbs'][] = ['label' => 'Kegiatan Tahunan Catatan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="catatan-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Kegiatan Tahunan Catatan</h3>
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
            [
                'attribute' => 'id_induk',
                'format' => 'raw',
                'value' => $model->id_induk,
            ],
            [
                'attribute' => 'id_user',
                'format' => 'raw',
                'value' => $model->id_user,
            ],
            [
                'attribute' => 'catatan',
                'format' => 'raw',
                'value' => $model->catatan,
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
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Kegiatan Tahunan Catatan', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Kegiatan Tahunan Catatan', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
