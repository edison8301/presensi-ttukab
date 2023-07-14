<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PengaturanBerlaku */

$this->title = "Detail Pengaturan Berlaku";
$this->params['breadcrumbs'][] = ['label' => 'Pengaturan Berlaku', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pengaturan-berlaku-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Pengaturan Berlaku</h3>
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
                'attribute' => 'id_pengaturan',
                'format' => 'raw',
                'value' => $model->id_pengaturan,
            ],
            [
                'attribute' => 'nilai',
                'format' => 'raw',
                'value' => $model->nilai,
            ],
            [
                'attribute' => 'tanggal_mulai',
                'format' => 'raw',
                'value' => $model->tanggal_mulai,
            ],
            [
                'attribute' => 'tanggal_selesai',
                'format' => 'raw',
                'value' => $model->tanggal_selesai,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Pengaturan Berlaku', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Pengaturan Berlaku', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
