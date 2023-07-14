<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\tandatangan\models\Berkas */

$this->title = "Detail Berkas";
$this->params['breadcrumbs'][] = ['label' => 'Berkas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="berkas-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Berkas</h3>
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
            [
                'attribute' => 'berkas_mentah',
                'format' => 'raw',
                'value' => $model->berkas_mentah,
            ],
            [
                'attribute' => 'berkas_tandatangan',
                'format' => 'raw',
                'value' => $model->berkas_tandatangan,
            ],
            [
                'attribute' => 'id_berkas_status',
                'format' => 'raw',
                'value' => $model->id_berkas_status,
            ],
            [
                'attribute' => 'nip_tandatangan',
                'format' => 'raw',
                'value' => $model->nip_tandatangan,
            ],
            [
                'attribute' => 'waktu_tandatangan',
                'format' => 'raw',
                'value' => $model->waktu_tandatangan,
            ],
            [
                'attribute' => 'id_aplikasi',
                'format' => 'raw',
                'value' => $model->id_aplikasi,
            ],
            [
                'attribute' => 'created_at',
                'format' => 'raw',
                'value' => $model->created_at,
            ],
            [
                'attribute' => 'updated_at',
                'format' => 'raw',
                'value' => $model->updated_at,
            ],
            [
                'attribute' => 'deleted_at',
                'format' => 'raw',
                'value' => $model->deleted_at,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Berkas', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Berkas', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
