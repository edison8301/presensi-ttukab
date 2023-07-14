<?php

use app\modules\kinerja\models\KegiatanBulanan;
use app\modules\kinerja\models\KegiatanHarianJenis;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\Helper;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model KegiatanBulanan */

$this->title = "Detail Kegiatan Bulan";
$this->params['breadcrumbs'][] = ['label' => 'Kegiatan Bulan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kegiatan-bulan-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Kegiatan Bulan</h3>
    </div>

    <div class="box-body">

    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            [
                'label' => 'Pegawai',
                'format' => 'raw',
                'value' => $model->getNamaNipPegawai(),
            ],
            [
                'label' => 'Nomor SKP',
                'format' => 'raw',
                'value' => $model->getNomorSkpLengkap(),
            ],
            [
                'label' => 'Kegiatan',
                'format' => 'raw',
                'value' => $model->kegiatanTahunan ? Html::a($model->kegiatanTahunan->nama,['/kinerja/kegiatan-tahunan/view','id'=>$model->id_kegiatan_tahunan]) : '',
            ],
            [
                'attribute' => 'bulan',
                'format' => 'raw',
                'value' => Helper::getBulanLengkap($model->bulan).' '.User::getTahun(),
            ],
            [
                'label' => 'Target',
                'format' => 'raw',
                'value' => $model->target.' '.$model->getSatuanKuantitas(),
            ],
            [
                'label' => 'Realisasi',
                'format' => 'raw',
                'value' => $model->getTotalRealisasi() !== null ? $model->getTotalRealisasi() : 0,
            ]
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-list"></i> Daftar Kegiatan Bulanan', [
            '/kinerja/kegiatan-bulanan/index',
            'KegiatanBulananSearch[bulan]'=>$model->bulan
        ], ['class' => 'btn btn-primary btn-flat']) ?>
    </div>

</div>

<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Daftar Kegiatan Harian</h3>
    </div>
    <div class="box-header">
        <?php if(@$model->kegiatanTahunan->id_pegawai==User::getIdPegawai()) { ?>
        <?= Html::a(
            '<i class="fa fa-plus"></i> Tambah Kegiatan Harian',
            [
                'kegiatan-harian/create',
                'tanggal' => date('Y-m-d'),
                'id_kegiatan_tahunan' => $model->id_kegiatan_tahunan,
                'id_kegiatan_harian_jenis' => KegiatanHarianJenis::KEGIATAN_SKP
            ],
            [
                'class' => 'btn btn-primary btn-flat'
            ]
        ) ?>
        <?php } ?>
    </div>
    <div class="box-body">
        <table class="table table-hover table-bordered">
            <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th class="text-center" width="3%">No</th>
                    <th class="text-center" width="100px">Tanggal</th>
                    <th>Uraian</th>
                    <th style="width:100px" class="text-center">Kuantitas</th>
                    <th style="width:150px" class="text-center">Waktu</th>
                    <th style="width:100px" class="text-center">Berkas</th>
                    <th style="width:100px" class="text-center">Status</th>
                    <th style="width:120px">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                <?php foreach ($model->manyKegiatanHarian as $kegiatanHarian): ?>
                    <tr>
                        <td class="text-center"><?= $i++; ?></td>
                        <td class="text-center"><?= Helper::getTanggalSingkat($kegiatanHarian->tanggal); ?></td>
                        <td><?= $kegiatanHarian->uraian; ?></td>
                        <td class="text-center"><?= $kegiatanHarian->kuantitas; ?></td>
                        <td class="text-center"><?= $kegiatanHarian->getWaktu(); ?></td>
                        <td class="text-center"><?= $kegiatanHarian->berkas; ?></td>
                        <td class="text-center"><?= $kegiatanHarian->kegiatanStatus ? $kegiatanHarian->kegiatanStatus->getLabel() : '' ; ?></td>
                        <td class="text-center">
                            <?php if($kegiatanHarian->accessSetPeriksa()) { ?>
                            <?= Html::a('<i class="fa fa-send-o"></i>', ['kegiatan-harian/set-periksa', 'id' => $kegiatanHarian->id],['data-toggle'=>'tooltip','title'=>'Periksa Kegiatan','onclick'=>'return confirm("Yakin akan mengirim kegiatan untuk diperiksa atasan?");']); ?>
                            <?php } ?>
                            
                            <?= Html::a('<i class="fa fa-eye"></i>', ['kegiatan-harian/view', 'id' => $kegiatanHarian->id]); ?>
                            
                            <?php if($kegiatanHarian->accessUpdate()) { ?>
                            <?= Html::a('<i class="fa fa-pencil"></i>', ['kegiatan-harian/update', 'id' => $kegiatanHarian->id]); ?>
                            <?php } ?>

                            <?php if($kegiatanHarian->accessDelete()) { ?>
                            <?= Html::a('<i class="fa fa-trash"></i>', ['kegiatan-harian/delete', 'id' => $kegiatanHarian->id], ['onclick'=>'return confirm("Yakin akan menghapus data?");']) ?>
                            <?php } ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
        </table>
    </div>
</div>
