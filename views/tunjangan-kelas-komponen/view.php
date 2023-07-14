<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TunjanganKelasKomponen */

$this->title = "Detail Tunjangan Kelas Komponen";
$this->params['breadcrumbs'][] = ['label' => 'Tunjangan Kelas Komponen', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tunjangan-kelas-komponen-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Tunjangan Kelas Komponen</h3>
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
                'attribute' => 'kelas_jabatan',
                'format' => 'raw',
                'value' => $model->kelas_jabatan,
            ],
            [
                'attribute' => 'id_tunjangan_komponen',
                'format' => 'raw',
                'value' => $model->id_tunjangan_komponen,
            ],
            [
                'attribute' => 'jumlah_tunjangan',
                'format' => 'raw',
                'value' => $model->jumlah_tunjangan,
            ],
            [
                'attribute' => 'status_aktif',
                'format' => 'raw',
                'value' => $model->status_aktif,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Tunjangan Kelas Komponen', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Tunjangan Kelas Komponen', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
