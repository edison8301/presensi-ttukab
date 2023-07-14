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
                            'label' => 'Rencana Kinerja yang Didukung',
                            'format' => 'raw',
                            'value' => @$model->kegiatanTahunanAtasan->nama . ' '. Html::a('<i class="fa fa-pencil"></i> Ubah',[
                                '/kinerja/kegiatan-tahunan/update-id-kegiatan-tahunan-atasan-v2',
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
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Kinerja Tahunan', ['update-v2', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?php } ?>

        <?= Html::a('<i class="fa fa-list"></i> Daftar Kinerja Tahunan', ['index-v2','mode'=>$model->mode], ['class' => 'btn btn-primary btn-flat']) ?>

        <?php if($model->accessSetPeriksa()) { ?>
        <?= Html::a('<i class="fa fa-send-o"></i> Periksa Kinerja', ['kegiatan-tahunan/set-periksa', 'id' => $model->id,'mode'=>$model->mode], ['class' => 'btn btn-warning btn-flat','onclick'=>'return confirm("Yakin akan mengirim kegiatan untuk diperiksa atasan?")']) ?>
        <?php } ?>

        <?php if($model->accessSetSetuju()) { ?>
        <?= Html::a('<i class="fa fa-check"></i> Setujui Kinerja', ['kegiatan-tahunan/set-setuju', 'id' => $model->id,'mode'=>$model->mode], ['class' => 'btn btn-success btn-flat','onclick'=>'return confirm("Yakin akan menyetujui kegiatan?")']) ?>
        <?php } ?>
        <?php if($model->accessSetTolak()) { ?>
        <?= Html::a('<i class="fa fa-remove"></i> Tolak Kinerja', ['kegiatan-tahunan/set-tolak', 'id' => $model->id,'mode'=>$model->mode], ['class' => 'btn btn-danger btn-flat','onclick'=>'return confirm("Yakin akan menolak kegiatan?")']) ?>
        <?php } ?>
    </div>
</div>

<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Daftar Target / Realisasi Bulanan</h3>
    </div>
    <div class="box-header">
        <?php if($model->accessCreateSub()) { ?>
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Tahapan Kegiatan',['kegiatan-tahunan/create-v2','id_pegawai'=>$model->id_pegawai,'id_induk' => $model->id],['class' => 'btn btn-primary btn-flat']); ?>
        <?php } ?>
    </div>
    <div class="box-body">
        <?php 
            $allKegiatanTahunanUtamaInduk = [];
            $allKegiatanTahunanTambahanInduk = [];

            if ($model->id_kegiatan_tahunan_jenis == 1) {
                $allKegiatanTahunanUtamaInduk[] = $model;
            }
            if ($model->id_kegiatan_tahunan_jenis == 2) {
                $allKegiatanTahunanTambahanInduk[] = $model;
            }
        ?>
        <?= $this->render('_matriks-kegiatan-tahunan-v2',[
            'allKegiatanTahunanUtamaInduk' => $allKegiatanTahunanUtamaInduk,
            'allKegiatanTahunanTambahanInduk' => $allKegiatanTahunanTambahanInduk,
        ]); ?>
    </div>
</div>


<div class="box box-success">
    <div class="box-header">
        <h3 class="box-title">Daftar Kinerja Tahunan Tahapan</h3>
    </div>

    <div class="box-body">
        <table class="table table-borderd table-hover">
            <tr>
                <th style="text-align:center; width:10px;">No</th>
                <th>Rencana Kinerja</th>
                <th style="text-align:center;">Status</th>
                <th style="text-align:center;">Apek</th>
                <th style="text-align:center;">IKI</th>
                <th style="text-align:center;">Target</th>
                <th style="text-align:center;">Total Rencana Target</th>
                <th style="text-align:center;">Total Rencana Realisasi</th>
            </tr>
            <?php if (count($model->manySub) == 0) { ?>
                <tr>
                    <td colspan="8">Tidak ada kinerja tahunan tahapan</td>
                </tr>
            <?php } ?>
            <?php $no=1; foreach($model->manySub as $sub) { ?>
                <tr>
                    <td style="text-align:center;" rowspan="4"><?= $no++ ?></td>
                    <td rowspan="4">
                        <?= Html::a($sub->nama, ['/kinerja/kegiatan-tahunan/view-v2', 'id' => $sub->id]) ?>
                    </td>
                    <td style="text-align:center;" rowspan="4"><?= $sub->getLabelIdKegiatanStatus() ?></td>
                    <td style="text-align:center;">Kuantitas</td>
                    <td style="width:200px;"><?= $sub->indikator_kuantitas ?></td>
                    <td style="text-align:center;width:100px;">
                        <?= $sub->target_kuantitas ?>
                        <?= $sub->satuan_kuantitas ?>
                    </td>
                    <td style="text-align:center;width:100px">
                        <?= $sub->getTotalTarget() ?>
                        <?= $sub->satuan_kuantitas; ?>
                    </td>
                    <td style="text-align:center;width:100px">
                        <?= $sub->getTotalRealisasi([
                            'attribute' => 'realisasi_kualitas'
                        ]); ?>
                        <?= $sub->satuan_kuantitas ?>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:center;">Kualitas</td>
                    <td style="width:200px;"><?= $sub->indikator_kualitas ?></td>
                    <td style="text-align:center;">
                        <?= $sub->target_kualitas ?>
                        <?= $sub->satuan_kualitas ?>
                    </td>
                    <td style="text-align:center;width:100px">
                        <?= $sub->getTotalTarget(['attribute' => 'target_kualitas']) ?>
                        <?= $sub->satuan_kualitas; ?>
                    </td>
                    <td style="text-align:center;width:100px">
                        <?= $sub->getTotalRealisasi([
                            'attribute' => 'realisasi_kualitas'
                        ]); ?>
                        <?= $sub->satuan_kualitas ?>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:center;">Waktu</td>
                    <td style="width:200px;"><?= $sub->indikator_waktu ?></td>
                    <td style="text-align:center;">
                        <?= $sub->target_waktu ?>
                        <?= $sub->satuan_waktu ?>
                    </td>
                    <td style="text-align:center;width:100px">
                        <?= $sub->getTotalTarget(['attribute' => 'target_waktu']) ?>
                        <?= $sub->satuan_waktu; ?>
                    </td>
                    <td style="text-align:center;width:100px">
                        <?= $sub->getTotalRealisasi([
                            'attribute' => 'realisasi_waktu'
                        ]); ?>
                        <?= $sub->satuan_waktu ?>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:center;">Biaya</td>
                    <td style="width:200px;"><?= $sub->indikator_biaya ?></td>
                    <td style="text-align:center;">
                        <?= $sub->target_biaya ?>
                        <?= $sub->satuan_biaya ?>
                    </td>
                    <td style="text-align:center;width:100px">
                        <?= $sub->getTotalTarget(['attribute' => 'target_biaya']) ?>
                        <?= $sub->satuan_biaya; ?>
                    </td>
                    <td style="text-align:center;width:100px">
                        <?= $sub->getTotalRealisasi([
                            'attribute' => 'realisasi_biaya'
                        ]); ?>
                        <?= $sub->satuan_biaya ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>
