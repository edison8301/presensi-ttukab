<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\KetidakhadiranCeklis */

$this->title = "Detail Ketidakhadiran Ceklis";
$this->params['breadcrumbs'][] = ['label' => 'Ketidakhadiran Ceklis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ketidakhadiran-ceklis-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Ketidakhadiran Ceklis</h3>
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
                'attribute' => 'id_jam_kerja',
                'format' => 'raw',
                'value' => $model->id_jam_kerja,
            ],
            [
                'attribute' => 'keterangan',
                'format' => 'raw',
                'value' => $model->keterangan,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Ketidakhadiran Ceklis', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Ketidakhadiran Ceklis', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
