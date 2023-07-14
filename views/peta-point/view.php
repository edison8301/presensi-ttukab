<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PetaPoint */

$this->title = "Detail Peta Point";
$this->params['breadcrumbs'][] = ['label' => 'Peta Point', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="peta-point-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Peta Point</h3>
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
                'attribute' => 'id_peta',
                'format' => 'raw',
                'value' => $model->id_peta,
            ],
            [
                'attribute' => 'urutan',
                'format' => 'raw',
                'value' => $model->urutan,
            ],
            [
                'attribute' => 'latitude',
                'format' => 'raw',
                'value' => $model->latitude,
            ],
            [
                'attribute' => 'longitude',
                'format' => 'raw',
                'value' => $model->longitude,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Peta Point', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Peta Point', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
