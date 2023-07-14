<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\SkpPerilaku */

$this->title = "Detail Skp Perilaku";
$this->params['breadcrumbs'][] = ['label' => 'Skp Perilaku', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="skp-perilaku-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Skp Perilaku</h3>
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
                'attribute' => 'id_skp_perilaku_jenis',
                'format' => 'raw',
                'value' => $model->id_skp_perilaku_jenis,
            ],
            [
                'attribute' => 'ekspektasi',
                'format' => 'raw',
                'value' => $model->ekspektasi,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Skp Perilaku', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Skp Perilaku', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
