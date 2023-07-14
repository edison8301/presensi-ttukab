<?php

use app\components\Helper;
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
                'attribute' => 'id_instansi',
                'format' => 'raw',
                'value' => $model->getNamaInstansi(),
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
            ]
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Ubah Absensi Manual Perangkat Daerah', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Absensi Manual Perangkat Daerah', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
