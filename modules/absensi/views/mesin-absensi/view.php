<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\MesinAbsensi */

$this->title = "Detail Mesin Absensi";
$this->params['breadcrumbs'][] = ['label' => 'Mesin Absensi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mesin-absensi-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Mesin Absensi</h3>
    </div>

    <div class="box-body">

    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            [
                'attribute' => 'id_instansi',
                'format' => 'raw',
                'value' => @$model->instansi->nama,
            ],
            [
                'attribute' => 'serialnumber',
                'format' => 'raw',
                'value' => $model->serialnumber,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Mesin Absensi', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Mesin Absensi', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
