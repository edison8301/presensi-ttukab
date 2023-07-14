<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\UploadPresensiLog */

$this->title = "Detail Upload Presensi Log";
$this->params['breadcrumbs'][] = ['label' => 'Upload Presensi Log', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="upload-presensi-log-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Upload Presensi Log</h3>
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
                'attribute' => 'badgenumber',
                'format' => 'raw',
                'value' => @$model->pegawai->namaNip,
            ],
            [
                'attribute' => 'checktime',
                'format' => 'dateTime',
                'value' => $model->checktime,
            ],
            [
                'attribute' => 'checktype',
                'format' => 'raw',
                'value' => $model->checktype,
            ],
            [
                'attribute' => 'SN',
                'format' => 'raw',
                'value' => $model->mesinAbsensi->snInstansi,
            ],
            [
                'attribute' => 'status_kirim',
                'format' => 'raw',
                'value' => $model->status_kirim,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Upload Presensi Log', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Upload Presensi Log', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
