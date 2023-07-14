<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\InstansiPegawaiFungsi */

$this->title = "Detail Instansi Pegawai Fungsi";
$this->params['breadcrumbs'][] = ['label' => 'Instansi Pegawai Fungsi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="instansi-pegawai-fungsi-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Instansi Pegawai Fungsi</h3>
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
                'attribute' => 'nama',
                'format' => 'raw',
                'value' => $model->nama,
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
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Instansi Pegawai Fungsi', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Instansi Pegawai Fungsi', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
