<?php

use app\components\Helper;
use app\widgets\LabelKegiatan;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @see \app\modules\kinerja\controllers\PegawaiController::actionViewV3() */
/* @var $this yii\web\View */
/* @var $model app\modules\tukin\models\Pegawai */

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
                [
                    'attribute' => 'id_instansi',
                    'format' => 'raw',
                    'value' => @$model->instansiPegawaiBerlaku->getNamaInstansi(),
                ],
                [
                    'attribute' => 'id_jabatan',
                    'format' => 'raw',
                    'value' => @$model->getNamaJabatan(),
                ],
                [
                    'label' => 'Kelas Jabatan',
                    'value' => @$model->getKelasJabatan()
                ],
                [
                    'attribute' => 'id_atasan',
                    'format' => 'raw',
                    'value' => @$model->instansiPegawaiBerlaku->atasan->nama,
                ],
            ],
        ]) ?>
    </div>
</div>

<?= $this->render('//filter/_filter-tahun'); ?>

<?= $this->render('_filter-index', [
    'action' => ['/kinerja/pegawai/view-v3','id' => $searchModel->id_pegawai],
    'searchModel' => $searchModel,
]); ?>

<div class="pegawai-view box box-primary">

    <div class="box-header with-border">
        <h2 class="box-title"> <i class="fa fa-calendar"></i> Daftar Kinerja Bulanan</h2>
    </div>

    <div class="box-body">
        <table class="table table-bordered">
            <tr>
                <th>No</th>
                <th>Kinerja Bulanan</th>
                <th style="text-align: center">Nomor SKP</th>
                <th style="text-align: center">Bulan</th>
                <th style="text-align: center">Jenis</th>
                <th style="text-align: center">Aspek</th>
                <th style="text-align: center; width: 100px;">Target</th>
                <th style="text-align: center">Realisasi</th>
                <th style="text-align: center">% Realisasi</th>
                <th>Status</th>
                <th>&nbsp;</th>
            </tr>
            <?php
                $total_persen = 0;
                $i = 1;
                $persenRealisasiTotal = 0;
                $jumlah = 0;
            ?>
            <?php foreach ($allKegiatanBulanan as $kegiatanBulanan) { ?>
                <?php $jumlah++ ?>
                <tr>
                    <td class="text-center"><?= $i++; ?></td>
                    <td>
                        <?= Html::a($kegiatanBulanan->kegiatanTahunan->nama,[
                            'kegiatan-bulanan/view-v3',
                            'id'=>$kegiatanBulanan->id
                        ]); ?>
                    </td>
                    <td class="text-center">
                        <?= @$kegiatanBulanan->instansiPegawaiSkp->nomor; ?>
                    </td>
                    <td class="text-center">
                        <?= $kegiatanBulanan->getNamaBulanSingkat(); ?>
                    </td>
                    <td style="text-align: center">
                        <?= $kegiatanBulanan->kegiatanTahunan ? $kegiatanBulanan->kegiatanTahunan->getTextInduk() : ''; ?>
                    </td>
                    <td style="text-align: center">
                        Kuantitas
                    </td>
                    <td style="text-align: center">
                        <?= $target = $kegiatanBulanan->target; ?> <?= $kegiatanBulanan->getSatuanKuantitas(); ?>
                    </td>
                    <td style="text-align: center">
                        <?php $label = Helper::rp($realisasi = $kegiatanBulanan->realisasi,0,0); ?>
                        <?php $label .= ' '.$kegiatanBulanan->getSatuanKuantitas(); ?>
                        <?= Html::a($label,[
                            '/kinerja/kegiatan-harian/index-v4',
                            'KegiatanHarianSearch[id_kegiatan_tahunan]'=>$kegiatanBulanan->id_kegiatan_tahunan,
                            'KegiatanHarianSearch[bulan]'=>$kegiatanBulanan->bulan,
                            'KegiatanHarianSearch[id_pegawai]'=>$kegiatanBulanan->kegiatanTahunan->id_pegawai
                        ]); ?>
                    </td>
                    <td style="text-align: center">
                        <?= Helper::rp($persenRealisasi = $kegiatanBulanan->persen_realisasi,0,1); ?>
                    </td>
                    <td style="text-align: center">
                        <?= @$kegiatanBulanan->kegiatanTahunan->labelIdKegiatanStatus; ?>
                    </td>
                    <td style="text-align: center">
                        <?= Html::a('<i class="fa fa-eye"></i>',['kegiatan-bulanan/view-v3','id'=>$kegiatanBulanan->id],['data-toggle'=>'tooltip','title'=>'Lihat']); ?>
                        <?= Html::a('<i class="fa fa-refresh"></i>',['kegiatan-bulanan/update-realisasi','id'=>$kegiatanBulanan->id],['data-toggle'=>'tooltip','title'=>'Perbarui Realisasi']); ?>
                    </td>
                </tr>
            <?php } ?>
            <tr>
                <th colspan="8" class="text-center">RATA - RATA</th>
                <th style="text-align: center"><?= Helper::rp($persenRealisasiTotal/$jumlah,0,1); ?></th>
                <th colspan="3">&nbsp;</th>
            </tr>
        </table>
    </div>
</div>

