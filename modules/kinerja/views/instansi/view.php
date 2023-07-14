<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Pegawai */

$this->title = "Detail Pegawai";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-view box box-primary">

    <div class="box-header with-border">
        <h3 class="box-title">Detail Pegawai</h3>
    </div>

    <div class="box-body">

    <?= DetailView::widget([
        'model' => $model,
        'template'=>'<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            [
                'attribute'=>'tahun',
                'format'=>'raw',
                'value'=>$model->tahun
            ],
            [
                'label'=>'Pegawai',
                'format'=>'raw',
                'value'=>$model->getRelationField("refPegawai","nama")
            ],
            [
                'label'=>'Instansi',
                'format'=>'raw',
                'value'=>$model->getRelationField("refInstansi","nama")
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Pegawai', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Pegawai', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>

<div class="pegawai-view box box-primary">

    <div class="box-header with-border">
        <h3 class="box-title">Kegiatan Tahunan</h3>
    </div>

    <div class="box-body">


    </div>

</div>
