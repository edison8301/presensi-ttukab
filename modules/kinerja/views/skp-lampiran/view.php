<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\SkpLampiran */

$this->title = "Detail Skp Lampiran";
$this->params['breadcrumbs'][] = ['label' => 'Skp Lampiran', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="skp-lampiran-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Skp Lampiran</h3>
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
                'attribute' => 'id_skp_lampiran_jenis',
                'format' => 'raw',
                'value' => $model->id_skp_lampiran_jenis,
            ],
            [
                'attribute' => 'uraian',
                'format' => 'raw',
                'value' => $model->uraian,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Skp Lampiran', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Skp Lampiran', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
