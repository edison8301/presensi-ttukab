<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\SkpIkiMik */

$this->title = "Detail Skp Iki Mik";
$this->params['breadcrumbs'][] = ['label' => 'Skp Iki Mik', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="skp-iki-mik-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Skp Iki Mik</h3>
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
                'attribute' => 'id_skp',
                'format' => 'raw',
                'value' => $model->id_skp,
            ],
            [
                'attribute' => 'id_skp_iki',
                'format' => 'raw',
                'value' => $model->id_skp_iki,
            ],
            [
                'attribute' => 'tujuan',
                'format' => 'raw',
                'value' => $model->tujuan,
            ],
            [
                'attribute' => 'definisi',
                'format' => 'raw',
                'value' => $model->definisi,
            ],
            [
                'attribute' => 'formula',
                'format' => 'raw',
                'value' => $model->formula,
            ],
            [
                'attribute' => 'satuan_pengukuran',
                'format' => 'raw',
                'value' => $model->satuan_pengukuran,
            ],
            [
                'attribute' => 'kualitas_tingkat_kendali',
                'format' => 'raw',
                'value' => $model->kualitas_tingkat_kendali,
            ],
            [
                'attribute' => 'sumber_data',
                'format' => 'raw',
                'value' => $model->sumber_data,
            ],
            [
                'attribute' => 'periode_pelaporan',
                'format' => 'raw',
                'value' => $model->periode_pelaporan,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Skp Iki Mik', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Skp Iki Mik', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
