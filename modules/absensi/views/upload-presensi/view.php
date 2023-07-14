<?php

use app\modules\absensi\models\UploadPresensiLog;
use app\widgets\Label;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\UploadPresensi */

$this->title = "Detail Upload Presensi";
$this->params['breadcrumbs'][] = ['label' => 'Upload Presensi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="upload-presensi-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Upload Presensi</h3>
    </div>

    <div class="box-body">

    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            [
                'attribute' => 'file',
                'format' => 'raw',
                'value' => $model->file,
            ],
            [
                'attribute' => 'user_pengupload',
                'format' => 'raw',
                'value' => $model->user_pengupload,
            ],
            [
                'attribute' => 'waktu_diupload',
                'format' => 'datetime',
                'value' => $model->waktu_diupload,
            ],
        ],
    ]) ?>

    </div>
    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Upload Presensi', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Upload Presensi', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>
</div>

<div class="box box-success">
    <div class="box-header">
        <h3 class="box-title">
            Log Presensi
        </h3>
    </div>
    <div class="box-body">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header' => 'No',
                'headerOptions' => ['style' => 'text-align:center'],
                'contentOptions' => ['style' => 'text-align:center']
            ],

            [
                'attribute' => 'badgenumber',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
                'value' => function ($data) {
                    return @$data->pegawai->namaNip;
                }
            ],
            [
                'attribute' => 'checktime',
                'format' => 'datetime',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'SN',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
                'value' => function ($data) {
                    return $data->mesinAbsensi->snInstansi;
                }
            ],
            [
                'attribute' => 'status_kirim',
                'format' => 'raw',
                'filter' => UploadPresensiLog::getListStatusLog(),
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
                'value' =>  function ($data) {
                    return Label::widget([
                        'text' => $data->getStatusKirim(),
                        'context' => function() use($data) {
                            if ($data->getIsStatusGagalKirim()) {
                                return "warning";
                            } elseif ($data->getIsStatusTerkirim()) {
                                return "success";
                            } elseif ($data->getIsStatusSudahTerkirim()) {
                                return "info";
                            } elseif ($data->getIsStatusNoUser()) {
                                return "danger";
                            }
                        }
                    ]);
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'text-align:center;width:80px']
            ],
        ],
    ]); ?>
    </div>
</div>
