<?php

use app\components\Helper;
use app\modules\kinerja\models\KegiatanTahunan;
use app\widgets\AlertKegiatan;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model KegiatanTahunan*/

$this->title = "Detail Kegiatan Tahunan";
$this->params['breadcrumbs'][] = ['label' => 'Kegiatan Tahunan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= AlertKegiatan::widget(['kegiatan' => $model]); ?>

<div class="kegiatan-tahunan-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Kegiatan Tahunan</h3>
    </div>

    <div class="box-body">
        <div class="row">
            <div class="col-sm-6">
                <?= DetailView::widget([
                    'model' => $model,
                    'template' => '<tr><th style="text-align:right; width: 180px">{label}</th><td>{value}</td></tr>',
                    'attributes' => [
                        [
                            'attribute' => 'id_pegawai',
                            'format' => 'raw',
                            'value' => $model->getNamaNipPegawai(),
                        ],
                        [
                            'label' => 'Nomor SKP',
                            'format' => 'raw',
                            'value' => $model->getNomorSkpLengkap(),
                        ],
                        [
                            'label' => 'Kegiatan Atasan yang Didukung',
                            'format' => 'raw',
                            'value' => @$model->kegiatanTahunanAtasan->nama . ' '. Html::a('<i class="fa fa-pencil"></i> Ubah',[
                                '/kinerja/kegiatan-tahunan/update-id-kegiatan-tahunan-atasan',
                                'id'=>$model->id
                            ],['class' => 'btn btn-success btn-flat btn-xs']),
                            'visible' => @$model->isVisibleIdKegiatanTahunanAtasan() AND $model->status_kegiatan_tahunan_atasan == 1
                        ],
                        [
                            'attribute' => 'nama',
                            'format' => 'raw',
                            'value' => Html::encode($model->nama),
                        ],
                        [
                            'label' => 'Target Kuantitas',
                            'format' => 'raw',
                            'value' => $model->getTargetSatuan(),
                        ],
                        [
                            'label' => 'Target Waktu',
                            'attribute' => 'waktu',
                            'format' => 'raw',
                            'value' => $model->target_waktu.' Bulan',
                        ],
                        'target_biaya',
                        [
                            'label' => 'Target AK',
                            'attribute' => 'waktu',
                            'format' => 'raw',
                            'value' => $model->target_angka_kredit,
                        ],
                        [
                            'attribute' => 'id_kegiatan_status',
                            'format' => 'raw',
                            'value' => $model->kegiatanStatus ? $model->kegiatanStatus->getLabel() : '',
                        ],
                    ],
                ]) ?>
            </div>
            <div class="col-sm-6">
                <?= DetailView::widget([
                    'model' => $model,
                    'template' => '<tr><th style="text-align:right; width: 180px">{label}</th><td>{value}</td></tr>',
                    'attributes' => [
                        [
                            'attribute' => 'id_pegawai_penyetuju',
                            'format' => 'raw',
                            'value' => $model->getRelationField('pegawaiPenyetuju', 'nama'),
                        ],
                        [
                            'attribute' => 'waktu_dibuat',
                            'format' => 'datetime',
                            'value' => $model->waktu_dibuat,
                        ],
                        [
                            'attribute' => 'waktu_disetujui',
                            'format' => 'datetime',
                            'value' => $model->waktu_disetujui,
                        ],
                    ],
                ]) ?>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <?php if($model->accessUpdate()) { ?>
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Kegiatan Tahunan', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?php } ?>

        <?= Html::a('<i class="fa fa-list"></i> Daftar Kegiatan Tahunan', ['index','mode'=>$model->mode], ['class' => 'btn btn-primary btn-flat']) ?>

        <?php if($model->accessSetPeriksa()) { ?>
        <?= Html::a('<i class="fa fa-send-o"></i> Periksa Kegiatan', ['kegiatan-tahunan/set-periksa', 'id' => $model->id,'mode'=>$model->mode], ['class' => 'btn btn-warning btn-flat','onclick'=>'return confirm("Yakin akan mengirim kegiatan untuk diperiksa atasan?")']) ?>
        <?php } ?>

        <?php if($model->accessSetSetuju()) { ?>
        <?= Html::a('<i class="fa fa-check"></i> Setujui Kegiatan', ['kegiatan-tahunan/set-setuju', 'id' => $model->id,'mode'=>$model->mode], ['class' => 'btn btn-success btn-flat','onclick'=>'return confirm("Yakin akan menyetujui kegiatan?")']) ?>
        <?php } ?>
        <?php if($model->accessSetTolak()) { ?>
        <?= Html::a('<i class="fa fa-remove"></i> Tolak Kegiatan', ['kegiatan-tahunan/set-tolak', 'id' => $model->id,'mode'=>$model->mode], ['class' => 'btn btn-danger btn-flat','onclick'=>'return confirm("Yakin akan menolak kegiatan?")']) ?>
        <?php } ?>
    </div>
</div>

<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Daftar Target / Realisasi Bulanan</h3>
    </div>
    <div class="box-header">
        <?php if($model->accessCreateSub()) { ?>
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Tahapan Kegiatan',['kegiatan-tahunan/create','id_pegawai'=>$model->id_pegawai,'id_induk' => $model->id],['class' => 'btn btn-primary btn-flat']); ?>
        <?php } ?>
    </div>
    <div class="box-body">
        <?php
            $allKegiatanTahunanInduk = [];
            $allKegiatanTahunanInduk[] = $model;
        ?>
        <?= $this->render('_matriks-kegiatan-tahunan',['allKegiatanTahunanInduk'=>$allKegiatanTahunanInduk]); ?>
        <div>&nbsp;</div>
        <div>&nbsp;</div>
        <div>&nbsp;</div>
        <div>&nbsp;</div>
    </div>
</div>

<?php /*
<?= $this->render('_kegiatan-riwayat', ['model' => $model]); ?>
 */ ?>

<?php /*
<?= $this->render('_kegiatan-bulanan', ['model' => $model]); ?>
<?= $this->render('_kegiatan-harian', ['model' => $model]); ?>
*/ ?>
