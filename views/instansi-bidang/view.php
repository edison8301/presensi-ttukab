<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\InstansiBidang */

$this->title = "Detail Instansi Bidang";
$this->params['breadcrumbs'][] = ['label' => 'Instansi Bidang', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="instansi-bidang-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Instansi Bidang</h3>
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
                'attribute' => 'id_instansi',
                'format' => 'raw',
                'value' => $model->id_instansi,
            ],
            [
                'attribute' => 'nama',
                'format' => 'raw',
                'value' => $model->nama,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Instansi Bidang', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Instansi Bidang', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
