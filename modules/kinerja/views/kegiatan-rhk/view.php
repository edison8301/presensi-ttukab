<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\KegiatanRhk */

$this->title = "Detail Kegiatan Rhk";
$this->params['breadcrumbs'][] = ['label' => 'Kegiatan Rhk', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kegiatan-rhk-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Kegiatan Rhk</h3>
    </div>

    <div class="box-body">

    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            [
                'attribute' => 'id_kegiatan_rhk_atasan',
                'format' => 'raw',
                'value' => $model->id_kegiatan_rhk_atasan,
            ],
            [
                'attribute' => 'id_pegawai',
                'format' => 'raw',
                'value' => @$model->pegawai->nama,
            ],
            [
                'attribute' => 'id_kegiatan_rhk_jenis',
                'format' => 'raw',
                'value' => @$model->kegiatanRhkJenis->nama,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Kegiatan Rhk', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Kegiatan Rhk', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
