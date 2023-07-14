<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\RekapJenis */

$this->title = "Detail Rekap Jenis";
$this->params['breadcrumbs'][] = ['label' => 'Rekap Jenis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rekap-jenis-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Rekap Jenis</h3>
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
                'attribute' => 'nama',
                'format' => 'raw',
                'value' => $model->nama,
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
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Rekap Jenis', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Rekap Jenis', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
