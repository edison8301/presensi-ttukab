<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\InstansiPegawaiSasaran */

$this->title = "Detail Instansi Pegawai Sasaran";
$this->params['breadcrumbs'][] = ['label' => 'Instansi Pegawai Sasaran', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="instansi-pegawai-sasaran-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Instansi Pegawai Sasaran</h3>
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
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Instansi Pegawai Sasaran', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Instansi Pegawai Sasaran', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
