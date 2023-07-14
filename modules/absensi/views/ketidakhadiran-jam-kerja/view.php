<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\Helper;

/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\Ketidakhadiran */

$this->title = "Detail Ketidakhadiran";
$this->params['breadcrumbs'][] = ['label' => 'Ketidakhadiran', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ketidakhadiran-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Ketidakhadiran</h3>
    </div>

    <div class="box-body">

    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            [
                'label'=>'Nama Pegawai',
                'attribute' => 'id_pegawai',
                'format' => 'raw',
                'value' => @$model->pegawai->nama,
            ],
            [
                'label' => 'NIP',
                'format' => 'raw',
                'value' => @$model->pegawai->nip,
            ],
            [
                'label' => 'Perangkat Daerah',
                'format' => 'raw',
                'value' => @$model->pegawai->getNamaInstansi(),
            ],
            [
                'attribute' => 'tanggal',
                'format' => 'raw',
                'value' => Helper::getHariTanggal($model->tanggal),
            ],
            [
                'attribute' => 'id_jam_kerja',
                'format' => 'raw',
                'value' => @$model->jamKerja->nama,
            ],
            [
                'attribute' => 'id_ketidakhadiran_jam_kerja_jenis',
                'format' => 'raw',
                'value' => @$model->ketidakhadiranJamKerjaJenis->nama,
            ],
            [
                'attribute' => 'id_ketidakhadiran_jam_kerja_status',
                'format' => 'raw',
                'value' => @$model->ketidakhadiranJamKerjaStatus->nama,
            ],
            [
                'attribute' => 'berkas',
                'format' => 'raw',
                'value' => $model->berkas,
            ],
            [
                'attribute' => 'keterangan',
                'format' => 'raw',
                'value' => $model->keterangan,
            ],
            [
                'attribute' => 'id_user_pembuat',
                'value' => @$model->userPembuat->username,
            ],
            [
                'attribute' => 'waktu_dibuat',
                'value' => Helper::getWaktuWIB($model->waktu_dibuat),
            ],
            [
                'attribute' => 'id_user_penyunting',
                'value' => @$model->userPenyunting->username,
            ],
            [
                'attribute' => 'waktu_disunting',
                'value' => Helper::getWaktuWIB($model->waktu_disunting),
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?php if($model->accessUpdate()) { ?>
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Ketidakhadiran', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?php } ?>
        <?= Html::a('<i class="fa fa-list"></i> Pengajuan Ketidakhadiran', ['index'], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('<i class="fa fa-calendar"></i> Rekap Absensi Pegawai ', ['/absensi/pegawai/view'], ['class' => 'btn btn-primary btn-flat']) ?>
    </div>

</div>
