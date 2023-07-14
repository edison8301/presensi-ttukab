<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\tukin\models\RefVariabelObjektif */

$this->title = "Detail Ref Variabel Objektif";
$this->params['breadcrumbs'][] = ['label' => 'Ref Variabel Objektif', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ref-variabel-objektif-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Ref Variabel Objektif</h3>
    </div>

    <div class="box-body">

    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            [
                'attribute' => 'kode',
                'format' => 'raw',
                'value' => $model->kode,
            ],
            [
                'attribute' => 'kelompok',
                'format' => 'raw',
                'value' => $model->kelompok,
            ],
            [
                'attribute' => 'uraian',
                'format' => 'raw',
                'value' => $model->uraian,
            ],
            [
                'attribute' => 'satuan',
                'format' => 'raw',
                'value' => $model->satuan,
            ],
            [
                'attribute' => 'tarif',
                'format' => 'raw',
                'value' => $model->tarif,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Ref Variabel Objektif', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Ref Variabel Objektif', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
