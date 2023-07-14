<?php

use app\components\Helper;
use app\widgets\PengumumanWidget;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Pengumuman */

$this->title = "Detail Pengumuman";
$this->params['breadcrumbs'][] = ['label' => 'Pengumuman', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pengumuman-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Pengumuman</h3>
    </div>

    <div class="box-body">

    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            [
                'attribute' => 'judul',
                'format' => 'raw',
                'value' => $model->judul,
            ],
            [
                'attribute' => 'teks',
                'format' => 'raw',
                'value' => $model->teks,
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => $model->status === 1 ? 'Aktif' : 'Tidak Aktif',
            ],
            [
                'attribute' => 'tanggal_mulai',
                'format' => 'raw',
                'value' => Helper::getTanggal($model->tanggal_mulai),
            ],
            [
                'attribute' => 'tanggal_selesai',
                'format' => 'raw',
                'value' => Helper::getTanggal($model->tanggal_selesai),
            ],
            [
                'attribute' => 'user_pembuat',
                'format' => 'raw',
                'value' => @$model->userPembuat->nama,
            ],
            [
                'attribute' => 'waktu_dibuat',
                'format' => 'datetime',
                'value' => $model->waktu_dibuat,
            ],
            [
                'attribute' => 'user_pengubah',
                'format' => 'raw',
                'value' => @$model->userPengubah->nama,
            ],
            [
                'attribute' => 'waktu_diubah',
                'format' => 'datetime',
                'value' => $model->waktu_diubah,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Pengumuman', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Pengumuman', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>
</div>
<div class="box box-solid">
    <div class="box-header with-border">
        <h3 class="box-title">Preview Pegawai</h3>
    </div>
    <div class="box-body">
        <?= PengumumanWidget::widget(['model' => $model, 'demo' => 'pegawai']); ?>
    </div>
    <div class="box-header with-border">
        <h3 class="box-title">Preview Instansi</h3>
    </div>
    <div class="box-body">
        <?= PengumumanWidget::widget(['model' => $model, 'demo' => 'instansi']); ?>
    </div>
</div>
