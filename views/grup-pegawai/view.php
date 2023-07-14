<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\GrupPegawai */

$this->title = "Detail Grup Pegawai";
$this->params['breadcrumbs'][] = ['label' => 'Grup Pegawai', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grup-pegawai-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Grup Pegawai</h3>
    </div>

    <div class="box-body">

    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            [
                'attribute' => 'id_grup',
                'format' => 'raw',
                'value' => @$model->grup->nama,
            ],
            [
                'attribute' => 'id_pegawai',
                'format' => 'raw',
                'value' => @$model->pegawai->nama,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Grup Pegawai', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Grup Pegawai', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>


<div class="box box-success">
    <div class="box-header">
        <h3 class="box-title">Daftar Shift Kerja</h3>
    </div>
    <div class="box-header">
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Shift Kerja', ['grup-pegawai/create', 'id_grup' => $model->id], ['class' => 'btn btn-primary btn-flat']); ?>
    </div>

</div>
