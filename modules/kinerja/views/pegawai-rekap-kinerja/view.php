<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;
use app\components\Helper;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\PegawaiRekapKinerja */

$this->title = "Detail Pegawai Rekap Kinerja";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Rekap Kinerja', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-rekap-kinerja-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Pegawai Rekap Kinerja</h3>
    </div>

    <div class="box-body">

        <?= DetailView::widget([
            'model' => $model,
            'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
            'attributes' => [
                [
                    'attribute' => 'id_pegawai',
                    'format' => 'raw',
                    'value' => $model->pegawai->nama . '<br>' . $model->pegawai->nip,
                ],
                [
                    'attribute' => 'id_instansi',
                    'format' => 'raw',
                    'value' => $model->instansi->nama,
                ],
                [
                    'attribute' => 'bulan',
                    'format' => 'raw',
                    'value' => $model->bulan,
                ],
                [
                    'attribute' => 'potongan_skp',
                    'format' => 'raw',
                    'value' => $model->potongan_skp,
                ],
                [
                    'attribute' => 'potongan_ckhp',
                    'format' => 'raw',
                    'value' => $model->potongan_ckhp,
                ],
                [
                    'attribute' => 'progres',
                    'value' => number_format($model->progres, 2) . '%',
                ],
                [
                    'attribute' => 'potongan_total',
                    'format' => 'raw',
                    'value' => $model->potongan_total . '%',
                ],
            ],
        ]) ?>

    </div>

</div>

<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">
            Daftar Kegiatan Bulanan : <?= Helper::getBulanLengkap($model->bulan) . ' ' . User::getTahun() ?>
        </h3>
    </div>
    <div class="box-body">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th style="text-align: center">No</th>
                <th style="text-align: center">SKP</th>
                <th style="text-align: center">Target</th>
                <th style="text-align: center">Realisasi</th>
                <th style="text-align: center">Capaian</th>
            </tr>
            </thead>
            <?php $i = 1; ?>
            <?php foreach ($model->manyKegiatanBulanan as $kegiatanBulanan) { ?>
                <tr>
                    <td style="text-align: center"><?= $i++; ?></td>
                    <td><?= Html::a(@$kegiatanBulanan->kegiatanTahunan->nama, ['kegiatan-tahunan/view', 'id' => @$kegiatanBulanan->kegiatanTahunan->id])?></td>
                    <td style="text-align: center"><?= $kegiatanBulanan->target . ' ' . @$kegiatanBulanan->kegiatanTahunan->satuan_kuantitas ?></td>
                    <td style="text-align: center"><?= $kegiatanBulanan->getTotalRealisasiBulan() ?></td>
                    <td style="text-align: center"><?= $kegiatanBulanan->getPersen() ?>%</td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>
