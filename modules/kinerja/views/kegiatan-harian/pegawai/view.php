<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\Helper;

/* @var $this yii\web\View */
/* @var $model app\models\KegiatanHarian */

$this->title = "Detail Kegiatan Harian";
$this->params['breadcrumbs'][] = ['label' => 'Kegiatan Harian', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kegiatan-harian-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Kegiatan Harian</h3>
    </div>

    <div class="box-body">

    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            [
                'attribute' => 'id_kegiatan_tahunan',
                'format' => 'raw',
                'value' => $model->kegiatanTahunan->nama_kegiatan,
            ],
            [
                'attribute' => 'tanggal',
                'format' => 'date',
                'value' => $model->tanggal,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Kegiatan Harian', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-eye"></i> Lihat Kegiatan Induk', ['kegiatan/view', 'id' => $model->id_kegiatan_tahunan], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
