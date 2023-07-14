<?php

/* @see \app\modules\kinerja\controllers\PegawaiController::actionViewTahunan() */
/* @var $this yii\web\View */
/* @var $model \app\modules\tukin\models\Pegawai */
/* @var $allKegiatanTahunan \app\modules\kinerja\models\KegiatanTahunan[] */

use app\components\Helper;
use yii\widgets\DetailView;

$this->title = "Detail Pegawai";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="pegawai-view box box-primary">

    <div class="box-header with-border">
        <h3 class="box-title">Detail Pegawai</h3>
    </div>

    <div class="box-body">
        <?= DetailView::widget([
            'model' => $model,
            'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
            'attributes' => [
                [
                    'attribute' => 'nama',
                    'format' => 'raw',
                    'value' => $model->nama,
                ],
                [
                    'attribute' => 'nip',
                    'format' => 'raw',
                    'value' => $model->nip,
                ],
            ],
        ]) ?>
    </div>
</div>

<?= $this->render('//filter/_filter-tahun'); ?>

<div class="pegawai-view box box-primary">

    <div class="box-header with-border">
        <h3 class="box-title">Daftar Kinerja Tahunan</h3>
    </div>

    <div class="box-body">
        <table class="table table-bordered">
            <tr>
                <th style="text-align: center; width: 50px;">No</th>
                <th>Kinerja Tahunan</th>
                <th style="text-align: center;">Nomor SKP</th>
                <th style="text-align: center;">Aspek</th>
                <th style="text-align: center; width: 150px;">Target</th>
                <th style="text-align: center; width: 150px;">Realisasi</th>
                <th style="text-align: center; width: 150px;">% Realisasi</th>
            </tr>
            <?php $no=1; foreach ($allKegiatanTahunan as $kegiatanTahunan) { ?>
                <tr>
                    <td style="text-align: center;" rowspan="4">
                        <?= $no++; ?>
                    </td>
                    <td rowspan="4">
                        <?= $kegiatanTahunan->nama ?>
                    </td>
                    <td style="text-align: center;" rowspan="4">
                        <?= $kegiatanTahunan->instansiPegawaiSkp->nomor ?>
                    </td>
                    <td style="text-align: center;">Kuantitas</td>
                    <td style="text-align: center;">
                        <?= $kegiatanTahunan->getTotalTarget([
                            'attribute' => 'target',
                        ]) ?>
                        <?= $kegiatanTahunan->satuan_kuantitas ?>
                    </td>
                    <td style="text-align: center;">
                        <?= $kegiatanTahunan->getTotalRealisasi([
                            'attribute' => 'realisasi_kuantitas'
                        ]); ?>
                        <?= $kegiatanTahunan->satuan_kuantitas ?>
                    </td>
                    <td style="text-align: center;" rowspan="4">
                        <?= Helper::rp($kegiatanTahunan->getTotalPersenRealisasi(), 0, 2); ?>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;">Kualitas</td>
                    <td style="text-align: center;">
                        <?= $kegiatanTahunan->getTotalTarget([
                            'attribute' => 'target_kualitas',
                        ]) ?>
                        <?= $kegiatanTahunan->satuan_kualitas ?>
                    </td>
                    <td style="text-align: center;">
                        <?= $kegiatanTahunan->getTotalRealisasi([
                            'attribute' => 'realisasi_kualitas'
                        ]); ?>
                        <?= $kegiatanTahunan->satuan_kualitas ?>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;">Waktu</td>
                    <td style="text-align: center;">
                        <?= $kegiatanTahunan->getTotalTarget([
                            'attribute' => 'target_waktu',
                        ]) ?>
                        <?= $kegiatanTahunan->satuan_waktu ?>
                    </td>
                    <td style="text-align: center;">
                        <?= $kegiatanTahunan->getTotalRealisasi([
                            'attribute' => 'realisasi_waktu'
                        ]); ?>
                        <?= $kegiatanTahunan->satuan_waktu ?>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;">Biaya</td>
                    <td style="text-align: center;">
                        <?= $kegiatanTahunan->getTotalTarget([
                            'attribute' => 'target_biaya',
                        ]) ?>
                        <?= $kegiatanTahunan->satuan_biaya ?>
                    </td>
                    <td style="text-align: center;">
                        <?= $kegiatanTahunan->getTotalRealisasi([
                            'attribute' => 'realisasi_biaya'
                        ]); ?>
                        <?= $kegiatanTahunan->satuan_biaya ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>
