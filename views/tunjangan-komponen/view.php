<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TunjanganKomponen */

$this->title = "Detail Tunjangan Komponen";
$this->params['breadcrumbs'][] = ['label' => 'Tunjangan Komponen', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tunjangan-komponen-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Tunjangan Komponen</h3>
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
                'attribute' => 'urutan',
                'format' => 'raw',
                'value' => $model->urutan,
            ],
            [
                'attribute' => 'status_hapus',
                'format' => 'raw',
                'value' => $model->status_hapus,
            ],
            [
                'attribute' => 'waktu_hapus',
                'format' => 'raw',
                'value' => $model->waktu_hapus,
            ],
            [
                'attribute' => 'id_user_hapus',
                'format' => 'raw',
                'value' => $model->id_user_hapus,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Tunjangan Komponen', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Tunjangan Komponen', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
