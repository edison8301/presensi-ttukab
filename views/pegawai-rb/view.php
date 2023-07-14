<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PegawaiRb */

$this->title = "Detail Pegawai Rb";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Rb', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-rb-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Pegawai Rb</h3>
    </div>

    <div class="box-body">

    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            [
                'attribute' => 'tahun',
                'format' => 'raw',
                'value' => $model->tahun,
            ],
            [
                'attribute' => 'tanggal',
                'format' => 'raw',
                'value' => $model->tanggal,
            ],
            [
                'attribute' => 'id_pegawai',
                'format' => 'raw',
                'value' => $model->id_pegawai,
            ],
            [
                'attribute' => 'id_pegawai_rb_jenis',
                'format' => 'raw',
                'label' => 'Jenis',
                'value' => $model->id_pegawai_rb_jenis,
            ],
            [
                'attribute' => 'status_realisasi',
                'format' => 'raw',
                'label' => 'Status',
                'value' => $model->status_realisasi,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Pegawai Rb', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Pegawai Rb', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
