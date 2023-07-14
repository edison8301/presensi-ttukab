<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\iclock\Checkinout */

$this->title = "Detail Checkinout";
$this->params['breadcrumbs'][] = ['label' => 'Checkinout', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="checkinout-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Checkinout</h3>
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
                'attribute' => 'userid',
                'format' => 'raw',
                'value' => $model->userid,
            ],
            [
                'attribute' => 'checktime',
                'format' => 'raw',
                'value' => $model->checktime,
            ],
            [
                'attribute' => 'checktype',
                'format' => 'raw',
                'value' => $model->checktype,
            ],
            [
                'attribute' => 'verifycode',
                'format' => 'raw',
                'value' => $model->verifycode,
            ],
            [
                'attribute' => 'SN',
                'format' => 'raw',
                'value' => $model->SN,
            ],
            [
                'attribute' => 'sensorid',
                'format' => 'raw',
                'value' => $model->sensorid,
            ],
            [
                'attribute' => 'WorkCode',
                'format' => 'raw',
                'value' => $model->WorkCode,
            ],
            [
                'attribute' => 'Reserved',
                'format' => 'raw',
                'value' => $model->Reserved,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Checkinout', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Checkinout', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
