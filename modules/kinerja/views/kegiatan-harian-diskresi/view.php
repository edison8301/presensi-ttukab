<?php

use app\components\Helper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\KegiatanHarianDiskresi */

$this->title = "Detail Kegiatan Harian Diskresi";
$this->params['breadcrumbs'][] = ['label' => 'Kegiatan Harian Diskresi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kegiatan-harian-diskresi-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Kegiatan Harian Diskresi</h3>
    </div>

    <div class="box-body">

    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            [
                'attribute' => 'id_pegawai',
                'format' => 'raw',
                'value' => $model->pegawai->nama,
            ],
            [
                'attribute' => 'tanggal',
                'format' => 'raw',
                'value' => Helper::getTanggal($model->tanggal),
            ],
            [
                'attribute' => 'keterangan',
                'format' => 'raw',
                'value' => $model->keterangan,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Kegiatan Harian Diskresi', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Kegiatan Harian Diskresi', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
