<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\KegiatanKetidakhadiran */

$this->title = "Detail Kegiatan Ketidakhadiran";
$this->params['breadcrumbs'][] = ['label' => 'Kegiatan Ketidakhadiran', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kegiatan-ketidakhadiran-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Kegiatan Ketidakhadiran</h3>
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
                'attribute' => 'nip',
                'format' => 'raw',
                'value' => $model->nip,
            ],
            [
                'attribute' => 'id_kegiatan_ketidakhadiran_jenis',
                'format' => 'raw',
                'value' => $model->id_kegiatan_ketidakhadiran_jenis,
            ],
            [
                'attribute' => 'penjelasan',
                'format' => 'raw',
                'value' => $model->penjelasan,
            ],
            [
                'attribute' => 'checktime',
                'format' => 'raw',
                'value' => $model->checktime,
            ],
            [
                'attribute' => 'foto_pendukung',
                'format' => 'raw',
                'value' => $model->foto_pendukung,
            ],
            [
                'attribute' => 'created_at',
                'format' => 'raw',
                'value' => $model->created_at,
            ],
            [
                'attribute' => 'updated_at',
                'format' => 'raw',
                'value' => $model->updated_at,
            ],
            [
                'attribute' => 'deleted_at',
                'format' => 'raw',
                'value' => $model->deleted_at,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Kegiatan Ketidakhadiran', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Kegiatan Ketidakhadiran', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
