<?php

use app\components\Helper;
use app\modules\kinerja\models\KegiatanTahunan;
use yii\helpers\Html;
use yii\widgets\DetailView;
use dosamigos\chartjs\ChartJs;

/* @var $this yii\web\View */
/* @var $model KegiatanTahunan */

$this->title = "Detail Indikator Kinerja Individu";
$this->params['breadcrumbs'][] = ['label' => 'RHK', 'url' => ['/kinerja/kegiatan-rhk/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="kegiatan-tahunan-view box box-primary">

    <div class="box-header with-border">
        <h3 class="box-title"><?= $this->title ?></h3>
    </div>

    <div class="box-body">
        <div class="row">
            <div class="col-sm-8">
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
                            'label' => 'Rencana Hasil Kerja',
                            'format' => 'raw',
                            'value' => @$model->kegiatanRhk->nama,
                        ],
                        [
                            'attribute' => 'nama',
                            'format' => 'raw',
                            'value' => Html::encode($model->nama),
                        ],
                        [
                            'label' => 'Target',
                            'format' => 'raw',
                            'value' => $model->target . ' ' . $model->satuan,
                        ],
                        [
                            'attribute' => 'perspektif',
                            'format' => 'raw',
                            'visible' => $model->isJpt(),
                            'value' => str_replace(';', '<br/>', $model->perspektif),
                        ],
                        [
                            'attribute' => 'id_kegiatan_status',
                            'format' => 'raw',
                            'value' => $model->kegiatanStatus ? $model->kegiatanStatus->getLabel() : '',
                        ],
                        [
                            'label' => 'Indikator Renstra (eSakip)',
                            'value' => $model->getNamaIndikatorRenstra(),
                        ]
                    ],
                ]) ?>
            </div>
            <div class="col-sm-4">
                <?= DetailView::widget([
                    'model' => $model,
                    'template' => '<tr><th style="text-align:right; width: 180px">{label}</th><td>{value}</td></tr>',
                    'attributes' => [
                        [
                            'attribute' => 'id_pegawai_penyetuju',
                            'format' => 'raw',
                            'value' => function (KegiatanTahunan $data) {
                                if ($data->pegawaiPenyetuju == null) {
                                    return null;
                                }

                                return @$data->pegawaiPenyetuju->nama . '<br/>' . @$data->pegawaiPenyetuju->nip;
                            },
                        ],
                        [
                            'attribute' => 'waktu_dibuat',
                            'format' => 'raw',
                            'value' => Helper::getWaktuWIB($model->waktu_dibuat),
                        ],
                        [
                            'attribute' => 'waktu_disetujui',
                            'format' => 'raw',
                            'value' => Helper::getWaktuWIB($model->waktu_disetujui),
                        ],
                    ],
                ]) ?>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <?php if($model->accessUpdate()) { ?>
            <?= Html::a('<i class="fa fa-pencil"></i> Sunting', [
                'update-v3',
                'id' => $model->id,
            ], ['class' => 'btn btn-primary btn-flat']) ?>
        <?php } ?>

        <?php if($model->accessSetPeriksa()) { ?>
            <?= Html::a('<i class="fa fa-send-o"></i> Periksa', [
                'kegiatan-tahunan/set-periksa-v3',
                'id' => $model->id,
            ], [
                'class' => 'btn btn-warning btn-flat',
                'onclick'=>'return confirm("Yakin akan mengirim data untuk diperiksa atasan?")',
            ]) ?>
        <?php } ?>

        <?php if($model->accessSetSetuju()) { ?>
            <?= Html::a('<i class="fa fa-check"></i> Setujui', [
                'kegiatan-tahunan/set-setuju',
                'id' => $model->id,
            ], [
                'class' => 'btn btn-success btn-flat',
                'onclick'=>'return confirm("Yakin akan menyetujui data?")',
            ]) ?>
        <?php } ?>
        <?php if($model->accessSetTolak()) { ?>
            <?= Html::a('<i class="fa fa-remove"></i> Tolak', [
                'kegiatan-tahunan/tolak',
                'id' => $model->id,
            ], [
                'class' => 'btn btn-danger btn-flat',
            ]) ?>
        <?php } ?>
    </div>
</div>

<div class="box box-primary">

    <div class="box-header with-border">
        <h3 class="box-title">Target / Realisasi Bulanan</h3>
    </div>

    <div class="box-body">

        <div style="overflow: auto;">
            <table class="table table-bordered" style="table-layout: fixed">
                <tr>
                    <th style="text-align: center; width: 50px;" rowspan="3">No</th>
                    <th style="text-align: center; width: 300px;" rowspan="3">Rencana Hasil Kerja</th>
                    <th style="text-align: center; width: 80px;" rowspan="3">Aspek</th>
                    <th style="text-align: center; width: 200px;" rowspan="3">Indikator Kinerja Individu</th>
                    <th style="text-align: center; width: 100px;" rowspan="3">Target</th>
                    <th style="text-align: center; width: 100px;" rowspan="3">Total Rencana Target</th>
                    <th style="text-align: center; width: 100px;" rowspan="3">Total Realisasi</th>
                    <th style="text-align: center; width: <?= 60*24 ?>px" colspan="24">Rencana Target / Realisasi Pada Bulan</th>
                </tr>
                <tr>
                    <?php for($i=1; $i<=12; $i++) { ?>
                        <th style="text-align: center;" colspan="2">
                            <?= Helper::getBulanSingkat($i) ?>
                        </th>
                    <?php } ?>
                </tr>
                <tr>
                    <?php for ($i = 1;$i <= 12;$i++) { ?>
                        <th style="text-align:center; width:60px"><span data-toggle="tooltip" title="Rencana Target">&nbsp;TRGT&nbsp;</span></th>
                        <th style="text-align:center; width:60px"><span data-toggle="tooltip" title="Realisasi">&nbsp;REAL&nbsp;</span></th>
                    <?php } ?>
                </tr>
                <tr>
                    <td style="text-align: center">1</td>
                    <td>
                        <?= @$model->kegiatanRhk->nama ?><br/>
                    </td>
                    <td><?= @$model->kegiatanAspek->nama ?></td>
                    <td>
                        <?= $model->nama ?>
                    </td>
                    <td style="text-align: center;">
                        <?= $model->target ?>
                        <?= $model->satuan ?>
                    </td>
                    <td style="text-align: center;">
                        <?= $model->getTotalTarget() ?>
                        <?= $model->satuan ?>
                    </td>
                    <td style="text-align: center;">
                        <?= $model->getTotalRealisasi() ?>
                        <?= $model->satuan ?>
                    </td>
                    <?php for ($i=1; $i<=12; $i++) { ?>
                        <?php $kegiatanBulanan = $model->findOrCreateKegiatanBulan($i); ?>

                        <td style="text-align: center;">
                            <?= $kegiatanBulanan->getEditable([
                                'attribute' => 'target',
                            ]); ?>
                        </td>
                        <td style="text-align: center;">
                            <?= Helper::rp($kegiatanBulanan->realisasi, 0); ?>
                        </td>
                    <?php } ?>
                <tr>
                    <td colspan="31">
                        <?php $model->getKurvaKinerjaBulanan() ?>
                    </td>
                <tr>
                </tr>
            </table>
        </div>

    </div>

</div>
