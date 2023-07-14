<?php

use app\components\Helper;
use app\models\InstansiPegawai;
use app\models\User;
use app\modules\kinerja\models\KegiatanHarian;
use app\modules\kinerja\models\KegiatanHarianJenis;
use app\modules\kinerja\models\KegiatanStatus;
use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $pegawai app\models\Pegawai */
/* @var $rekapHarianForm \app\modules\kinerja\models\RekapHarianForm */

$this->title = "Rekap Kinerja Harian";
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="pegawai-view box box-primary">
    <div class="box-header">
        <h3 class="box-title">Data Pegawai</h3>
    </div>
    <div class="box-body">
        <?= DetailView::widget([
            'model' => $pegawai,
            'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
            'attributes' => [
                [
                    'attribute' => 'nama',
                    'format' => 'raw',
                    'value' => $pegawai->nama,
                ],
                [
                    'attribute' => 'nip',
                    'format' => 'raw',
                    'value' => $pegawai->nip,
                ]
            ],
        ])?>
    </div>
</div>

<?= $this->render('_filter-view-rekap-kegiatan-harian-v3', [
    'model' => $pegawai,
    'rekapHarianForm' => $rekapHarianForm,
]); ?>

<div class="pegawai-view box box-primary">
    <div class="box-header">
        <h3 class="box-title">Rekap Kinerja Harian</h3>
    </div>
    <div class="box-body">
        <table class="table table-hover table-bordered">
        <thead>
        <tr>
            <th rowspan="2" style="text-align: center; width:100px">Tanggal</th>
            <th rowspan="2" style="text-align: center; width:150px">Hari</th>
            <th colspan="4" style="text-align: center">Jumlah Kinerja Harian</th>
            <th rowspan="2" style="text-align: center">% Potong</th>
            <th rowspan="2">&nbsp</th>
        </tr>
        <tr>
            <th style="text-align: center">Konsep</th>
            <th style="text-align: center">Periksa</th>
            <th style="text-align: center">Tolak</th>
            <th style="text-align: center">Setuju</th>
        </tr>
        </thead>
        <?php $totalPotongan = 0; ?>
        <?php $date = $rekapHarianForm->getDate();?>
        <?php $end = $date->format('t'); ?>
        <?php for ($j = 1; $j <= $end; $j++) { ?>
            <?php $day = sprintf('%02d', $j)?>
            <?php $tanggal = $date->format("Y-m-$day");?>
            <tr class="tanggal tanggal-<?= $date->format("Y-m-$j");?>" data-tanggal="<?= $date->format("Y-m-$j");?>" style="cursor:pointer;" data-tanggal="<?= $tanggal;?>">
                <td style="text-align: center"><?= Helper::getTanggalSingkat($tanggal);?></td>
                <td style="text-align: center"><?= Helper::getHari($tanggal);?></td>
                <td style="text-align: center">
                    <?php $label = $pegawai->getCountAllKegiatanHarian([
                        'tanggal' => $tanggal,
                        'id_kegiatan_status' => KegiatanStatus::KONSEP,
                        'id_kegiatan_harian_versi' => 2,
                    ]); ?>
                    <?= Html::a($label,[
                        '/kinerja/kegiatan-harian/index-v3',
                        'KegiatanHarianSearch[tanggal]'=>$tanggal,
                        'KegiatanHarianSearch[id_kegiatan_status]'=>KegiatanStatus::KONSEP
                    ],[
                        'data-toggle'=>'tooltip',
                        'title'=>'Jumlah Kinerja Konsep'
                    ]); ?>
                </td>
                <td style="text-align: center">
                    <?php $label = $pegawai->getCountAllKegiatanHarian([
                        'tanggal' => $tanggal,
                        'id_kegiatan_status' => KegiatanStatus::PERIKSA,
                        'id_kegiatan_harian_versi' => 2,
                    ]); ?>
                    <?= Html::a($label,[
                        '/kinerja/kegiatan-harian/index-v3',
                        'KegiatanHarianSearch[tanggal]'=>$tanggal,
                        'KegiatanHarianSearch[id_kegiatan_status]'=>KegiatanStatus::PERIKSA
                    ],[
                        'data-toggle'=>'tooltip',
                        'title'=>'Jumlah Kinerja Konsep'
                    ]); ?>
                </td>
                <td style="text-align: center">
                    <?php $label = $pegawai->getCountAllKegiatanHarian([
                        'tanggal' => $tanggal,
                        'id_kegiatan_status' => KegiatanStatus::TOLAK,
                        'id_kegiatan_harian_versi' => 2,
                    ]); ?>
                    <?= Html::a($label,[
                        '/kinerja/kegiatan-harian/index-v3',
                        'KegiatanHarianSearch[tanggal]'=>$tanggal,
                        'KegiatanHarianSearch[id_kegiatan_status]'=>KegiatanStatus::TOLAK
                    ],[
                        'data-toggle'=>'tooltip',
                        'title'=>'Jumlah Kinerja Konsep'
                    ]); ?>
                </td>
                <td style="text-align: center">
                    <?php $label = $pegawai->getCountAllKegiatanHarian([
                        'tanggal' => $tanggal,
                        'id_kegiatan_status' => KegiatanStatus::SETUJU,
                        'id_kegiatan_harian_versi' => 2,
                    ]); ?>
                    <?= Html::a($label,[
                        '/kinerja/kegiatan-harian/index-v3',
                        'KegiatanHarianSearch[tanggal]'=>$tanggal,
                        'KegiatanHarianSearch[id_kegiatan_status]'=>KegiatanStatus::SETUJU
                    ],[
                        'data-toggle'=>'tooltip',
                        'title'=>'Jumlah Kinerja Konsep'
                    ]); ?>
                </td>
                <td style="text-align: center">
                    <?= $pegawai->getStringPotonganCkhp(['tanggal' => $tanggal]) ?>
                </td>
                <td style="text-align: center">
                    <?php if ($pegawai->canAccessKegiatanHarian(['tanggal' => $tanggal])) { ?>
                        <?= Html::a('<i class="fa fa-plus-square"></i>', [
                            '/kinerja/kegiatan-harian/create-v3',
                            'id_kegiatan_harian_jenis' => KegiatanHarianJenis::UTAMA,
                            'tanggal' => $tanggal,
                        ]) ?>
                        <?= Html::a('<i class="fa fa-plus-square-o"></i>', [
                            '/kinerja/kegiatan-harian/create-v3',
                            'id_kegiatan_harian_jenis' => KegiatanHarianJenis::TAMBAHAN,
                            'tanggal' => $tanggal,
                        ]) ?>
                    <?php } ?>
                </td>
            </tr>
        <?php } //END FOR ?>
        <tr>
            <th style="text-align: center;" colspan="6">Total</th>
            <th style="text-align: center;">
                <?= $pegawai->getTotalPotonganCkhp([
                    'bulan' => $rekapHarianForm->bulan
                ]) ?>
            </th>
            <th></th>
        </tr>
        </table>
    </div>
</div>
