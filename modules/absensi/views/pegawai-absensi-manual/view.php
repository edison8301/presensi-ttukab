<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PegawaiAbsensiManual */

$this->title = "Detail Pegawai Absensi Manual";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Absensi Manual', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-absensi-manual-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Pegawai Absensi Manual</h3>
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
                'attribute' => 'tanggal_mulai',
                'format' => 'raw',
                'value' => $model->tanggal_mulai,
            ],
            [
                'attribute' => 'tanggal_selesai',
                'format' => 'raw',
                'value' => $model->tanggal_selesai,
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
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Pegawai Absensi Manual', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Pegawai Absensi Manual', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
