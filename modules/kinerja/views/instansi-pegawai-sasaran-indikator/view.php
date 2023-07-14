<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\InstansiPegawaiSasaranIndikator */

$this->title = "Detail Instansi Pegawai Sasaran Indikator";
$this->params['breadcrumbs'][] = ['label' => 'Instansi Pegawai Sasaran Indikator', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="instansi-pegawai-sasaran-indikator-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Instansi Pegawai Sasaran Indikator</h3>
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
                'attribute' => 'id_instansi_pegawai_sasaran',
                'format' => 'raw',
                'value' => $model->id_instansi_pegawai_sasaran,
            ],
            [
                'attribute' => 'nama',
                'format' => 'raw',
                'value' => $model->nama,
            ],
            [
                'attribute' => 'penjelasan',
                'format' => 'raw',
                'value' => $model->penjelasan,
            ],
            [
                'attribute' => 'sumber_data',
                'format' => 'raw',
                'value' => $model->sumber_data,
            ],
            [
                'attribute' => 'urutan',
                'format' => 'raw',
                'value' => $model->urutan,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Instansi Pegawai Sasaran Indikator', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Instansi Pegawai Sasaran Indikator', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
