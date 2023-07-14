<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\PegawaiDispensasi */

$this->title = "Detail Pegawai Dispensasi";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Dispensasi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-dispensasi-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Pegawai Dispensasi</h3>
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
                'attribute' => 'tanggal_akhir',
                'format' => 'raw',
                'value' => $model->tanggal_akhir,
            ],
            [
                'attribute' => 'status_hapus',
                'format' => 'raw',
                'value' => $model->status_hapus,
            ],
            [
                'attribute' => 'user_pembuat',
                'format' => 'raw',
                'value' => @$model->userPembuat->username,
            ],
            [
                'attribute' => 'waktu_dibuat',
                'format' => 'raw',
                'value' => $model->waktu_dibuat,
            ],
            [
                'attribute' => 'user_pengubah',
                'format' => 'raw',
                'value' => @$model->userPengubah->username,
            ],
            [
                'attribute' => 'waktu_diubah',
                'format' => 'raw',
                'value' => $model->waktu_diubah,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Pegawai Dispensasi', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Pegawai Dispensasi', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
