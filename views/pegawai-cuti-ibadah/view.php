<?php

use app\components\Helper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PegawaiCutiIbadah */

$this->title = "Detail Pegawai Cuti Ibadah";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Cuti Ibadah', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-cuti-ibadah-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Pegawai Cuti Ibadah</h3>
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
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Pegawai Cuti Ibadah', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Pegawai Cuti Ibadah', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
