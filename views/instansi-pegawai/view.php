<?php

use app\components\Helper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\InstansiPegawai */

$this->title = "Detail Mutasi / Promosi Pegawai";
$this->params['breadcrumbs'][] = ['label' => 'Instansi Pegawai', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="instansi-pegawai-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Mutasi / Promosi Pegawai</h3>
    </div>

    <div class="box-body">

    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            [
                'attribute' => 'id_instansi',
                'format' => 'raw',
                'value' => @$model->instansi->nama,
            ],
            [
                'attribute' => 'id_pegawai',
                'format' => 'raw',
                'value' => @$model->pegawai->nama,
            ],
            [
                'attribute' => 'id_jabatan',
                'format' => 'raw',
                'value' => @$model->jabatan->nama,
            ],
            [
                'label' => 'Atasan',
                'format' => 'raw',
                'value' => @$model->jabatanAtasan->nama,
            ],
            [
                'attribute' => 'tanggal_berlaku',
                'format' => 'raw',
                'value' => Helper::getTanggalSingkat($model->tanggal_berlaku),
            ],
            [
                'attribute' => 'tanggal_mulai',
                'format' => 'raw',
                'value' => Helper::getTanggalSingkat($model->tanggal_mulai),
            ],
            [
                'attribute' => 'tanggal_selesai',
                'format' => 'raw',
                'value' => Helper::getTanggalSingkat($model->tanggal_selesai),
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-list"></i> Daftar Mutasi / Promosi', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
