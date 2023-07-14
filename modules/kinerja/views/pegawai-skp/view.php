<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\PegawaiSkp */

$this->title = "Detail Pegawai Skp";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Skp', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-skp-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Pegawai Skp</h3>
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
                'attribute' => 'id_instansi_pegawai',
                'format' => 'raw',
                'value' => $model->id_instansi_pegawai,
            ],
            [
                'attribute' => 'id_pegawai',
                'format' => 'raw',
                'value' => $model->id_pegawai,
            ],
            [
                'attribute' => 'id_instansi',
                'format' => 'raw',
                'value' => $model->id_instansi,
            ],
            [
                'attribute' => 'id_jabatan',
                'format' => 'raw',
                'value' => $model->id_jabatan,
            ],
            [
                'attribute' => 'id_golongan',
                'format' => 'raw',
                'value' => $model->id_golongan,
            ],
            [
                'attribute' => 'id_eselon',
                'format' => 'raw',
                'value' => $model->id_eselon,
            ],
            [
                'attribute' => 'nomor',
                'format' => 'raw',
                'value' => $model->nomor,
            ],
            [
                'attribute' => 'urutan',
                'format' => 'raw',
                'value' => $model->urutan,
            ],
            [
                'attribute' => 'tahun',
                'format' => 'raw',
                'value' => $model->tahun,
            ],
            [
                'attribute' => 'id_atasan',
                'format' => 'raw',
                'value' => $model->id_atasan,
            ],
            [
                'attribute' => 'tanggal_berlaku',
                'format' => 'raw',
                'value' => $model->tanggal_berlaku,
            ],
            [
                'attribute' => 'status_hapus',
                'format' => 'raw',
                'value' => $model->status_hapus,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Pegawai Skp', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Pegawai Skp', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
