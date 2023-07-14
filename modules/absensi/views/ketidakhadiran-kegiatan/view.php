<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\Helper;

/* @var $this yii\web\View */
/* @var $model app\models\KetidakhadiranKegiatan */

$this->title = "Detail Ketidakhadiran Kegiatan";
$this->params['breadcrumbs'][] = ['label' => 'Ketidakhadiran Kegiatan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ketidakhadiran-kegiatan-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Ketidakhadiran Kegiatan</h3>
    </div>

    <div class="box-body">

    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            [
                'attribute' => 'id_pegawai',
                'format' => 'raw',
                'value' => @$model->pegawai->nama,
            ],
            [
                'attribute' => 'tanggal',
                'format' => 'raw',
                'value' => Helper::getTanggal($model->tanggal),
            ],
            [
                'label'=>'Jenis',
                'attribute' => 'id_ketidakhadiran_kegiatan_jenis',
                'format' => 'raw',
                'value' => @$model->ketidakhadiranKegiatanJenis->nama,
            ],
            [
                'attribute' => 'id_ketidakhadiran_kegiatan_keterangan',
                'format' => 'raw',
                'value' => @$model->ketidakhadiranKegiatanKeterangan->nama,
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
        <?php if($model->accessUpdate()) { ?>
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Ketidakhadiran Kegiatan', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?php } ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Ketidakhadiran Kegiatan', ['index'], ['class' => 'btn btn-primary btn-flat']) ?>
    </div>

</div>
