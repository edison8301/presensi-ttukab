<?php

use app\components\Helper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PegawaiPenghargaan */

$this->title = "Detail Pegawai Penghargaan";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Penghargaan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-penghargaan-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Pegawai Penghargaan</h3>
    </div>

    <div class="box-body">

    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            [
                'attribute' => 'id_pegawai',
                'format' => 'raw',
                'value' => $model->pegawai->nama,
            ],
            [
                'attribute' => 'nama',
                'format' => 'raw',
                'value' => $model->nama,
            ],
            [
                'attribute' => 'tanggal',
                'format' => 'raw',
                'value' => Helper::getTanggal($model->tanggal),
            ],
            [
                'attribute' => 'id_pegawai_penghargaan_status',
                'label' => 'Status',
                'format' => 'raw',
                'value' => $model->getLabelPegawaiPenghargaanStatus(),
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Pegawai Penghargaan', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Pegawai Penghargaan', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
