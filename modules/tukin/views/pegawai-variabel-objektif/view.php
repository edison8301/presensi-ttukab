<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\tukin\models\PegawaiVariabelObjektif */

$this->title = "Detail Pegawai Variabel Objektif";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Variabel Objektif', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-variabel-objektif-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Pegawai Variabel Objektif</h3>
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
                'attribute' => 'id_pegawai',
                'format' => 'raw',
                'value' => $model->id_pegawai,
            ],
            [
                'attribute' => 'id_ref_variabel_objektif',
                'format' => 'raw',
                'value' => $model->id_ref_variabel_objektif,
            ],
            [
                'attribute' => 'bulan_mulai',
                'format' => 'raw',
                'value' => $model->bulan_mulai,
            ],
            [
                'attribute' => 'bulan_selesai',
                'format' => 'raw',
                'value' => $model->bulan_selesai,
            ],
            [
                'attribute' => 'tahun',
                'format' => 'raw',
                'value' => $model->tahun,
            ],
            [
                'attribute' => 'tarif',
                'format' => 'raw',
                'value' => $model->tarif,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Pegawai Variabel Objektif', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Pegawai Variabel Objektif', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
