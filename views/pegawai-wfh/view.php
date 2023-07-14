<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PegawaiWfh */

$this->title = "Detail Pegawai Wfh";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Wfh', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-wfh-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Pegawai Wfh</h3>
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
                'attribute' => 'id_pegawai',
                'format' => 'raw',
                'value' => $model->id_pegawai,
            ],
            [
                'attribute' => 'tanggal',
                'format' => 'raw',
                'value' => $model->tanggal,
            ],
            [
                'attribute' => 'keterangan',
                'format' => 'raw',
                'value' => $model->keterangan,
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
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Pegawai Wfh', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Pegawai Wfh', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
