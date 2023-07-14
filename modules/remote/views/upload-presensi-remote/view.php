<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\remote\models\UploadPresensiRemote */

$this->title = "Detail Upload Presensi Remote";
$this->params['breadcrumbs'][] = ['label' => 'Upload Presensi Remote', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="upload-presensi-remote-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Upload Presensi Remote</h3>
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
                'attribute' => 'id_queue',
                'format' => 'raw',
                'value' => $model->id_queue,
            ],
            [
                'attribute' => 'SN',
                'format' => 'raw',
                'value' => $model->SN,
            ],
            [
                'attribute' => 'file',
                'format' => 'raw',
                'value' => $model->file,
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => $model->status,
            ],
            [
                'attribute' => 'user_pengupload',
                'format' => 'raw',
                'value' => $model->user_pengupload,
            ],
            [
                'attribute' => 'waktu_diupload',
                'format' => 'raw',
                'value' => $model->waktu_diupload,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Upload Presensi Remote', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Upload Presensi Remote', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
