<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\Helper;

/* @var $this yii\web\View */
/* @var $model \app\modules\kinerja\models\KegiatanHarian */

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
                'label' => 'Pegawai',
                'format' => 'raw',
                'value' => Html::encode(@$model->pegawai->nama),
            ],
            [
                'format' => 'raw',
                'label' => 'Rencana Hasil Kerja',
                'value' => @$model->kegiatanTahunan->kegiatanRhk->nama,
            ],
            [
                'attribute' => 'id_kegiatan_tahunan',
                'format' => 'raw',
                'label' => 'Indikator Kinerja Individu',
                'value' => Html::encode($model->getNamaKegiatanTahunan()),
            ],
            [
                'attribute' => 'uraian',
                'format' => 'raw',
                'value' => Html::encode($model->uraian),
            ],
            [
                'attribute' => 'tanggal',
                'format' => 'date',
                'value' => Html::encode($model->tanggal),
            ],
            [
                'attribute' => 'jam_mulai',
                'value' => Html::encode($model->jam_mulai),
            ],
            [
                'attribute' => 'jam_selesai',
                'value' => Html::encode($model->jam_selesai),
            ],
            [
                'attribute' => 'id_kegiatan_status',
                'format' => 'raw',
                'value' => @$model->kegiatanStatus->getLabel(),
            ],
            [
                'attribute' => 'waktu_dibuat',
                'format' => 'raw',
                'value' => $model->getStringWaktuDibuat(),
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?php if($model->accessUpdate()) { ?>
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Kegiatan Harian', ['update-v4', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat','visible'=>1]) ?>
        <?php } ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Kegiatan Harian', ['kegiatan-harian/index-v4','mode'=>$model->mode], ['class' => 'btn btn-primary btn-flat']) ?>
        <?php if($model->accessSetPeriksa()) { ?>
        <?= Html::a('<i class="fa fa-send-o"></i> Periksa Kegiatan', ['kegiatan-harian/set-periksa', 'id' => $model->id,'mode'=>$model->mode], ['class' => 'btn btn-warning btn-flat','onclick'=>'return confirm("Yakin akan memeriksakan kegiatan?")']) ?>
        <?php } ?>
        <?php if($model->accessSetSetuju()) { ?>
        <?= Html::a('<i class="fa fa-check"></i> Setujui Kegiatan', ['kegiatan-harian/set-setuju', 'id' => $model->id,'mode'=>$model->mode], ['class' => 'btn btn-success btn-flat','onclick'=>'return confirm("Yakin akan menyetujui kegiatan?")']) ?>
        <?php } ?>
        <?php if($model->accessSetTolak()) { ?>
        <?= Html::a('<i class="fa fa-remove"></i> Tolak Kegiatan', ['kegiatan-harian/set-tolak', 'id' => $model->id,'mode'=>$model->mode], ['class' => 'btn btn-danger btn-flat','onclick'=>'return confirm("Yakin akan menolak kegiatan?")']) ?>
        <?php } ?>
    </div>

</div>


<?= $this->render('_kegiatan-riwayat', ['model' => $model, ]); ?>
