<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\SkpPerilakuJenis */

$this->title = "Detail Skp Perilaku Jenis";
$this->params['breadcrumbs'][] = ['label' => 'Skp Perilaku Jenis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="skp-perilaku-jenis-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Skp Perilaku Jenis</h3>
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
                'attribute' => 'nama',
                'format' => 'raw',
                'value' => $model->nama,
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
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Skp Perilaku Jenis', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Skp Perilaku Jenis', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
