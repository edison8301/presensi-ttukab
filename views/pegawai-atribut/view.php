<?php

use app\components\Helper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PegawaiAtribut */

$this->title = "Detail Pegawai Seragam Dinas";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Atribut', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-atribut-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Pegawai Seragam Dinas</h3>
    </div>

    <div class="box-body">
        <?= DetailView::widget([
            'model' => $model,
            'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
            'attributes' => [
                [
                    'attribute' => 'id_pegawai',
                    'format' => 'raw',
                    'value' => @$model->pegawai->nama,
                ],
                [
                    'attribute' => 'keterangan',
                    'format' => 'raw',
                    'value' => $model->keterangan,
                ],
                [
                    'attribute' => 'tanggal',
                    'format' => 'raw',
                    'value' => Helper::getTanggal($model->tanggal),
                ],
            ],
        ]) ?>
    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Data', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Data', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
