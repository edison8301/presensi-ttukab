<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use app\components\Helper;

/* @var $this yii\web\View */
/* @var $model app\models\PegawaiSertifikasi */

$this->title = "Detail Pegawai Sertifikasi";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Sertifikasi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-sertifikasi-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Pegawai Sertifikasi</h3>
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
                'attribute' => 'biaya',
                'format' => 'raw',
                'value' => Helper::rp($model->biaya),
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Pegawai Sertifikasi', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Pegawai Sertifikasi', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
