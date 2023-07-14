<?php

use app\components\Helper;
use app\models\InstansiPegawai;
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

$this->title = "Rekap Kegiatan Harian";
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('//filter/_filter-tahun'); ?>

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

<div class="pegawai-view box box-primary">
    <div class="box-header">
        <h3 class="box-title">Rekap Kegiatan Harian</h3>
    </div>
    <div class="box-body">
        <table class="table table-hover table-bordered">
        <thead>
        <tr>
            <th rowspan="2" style="text-align: center; width:100px">Tanggal</th>
            <th rowspan="2" style="text-align: center; width:150px">Hari</th>
            <th colspan="4" style="text-align: center">Jumlah Kegiatan</th>
            <th rowspan="2" style="text-align: center">% Potong</th>
            <th rowspan="2" style="text-align: center">Keterangan</th>
            <th rowspan="2">&nbsp</th>
        </tr>
        <tr>
            <th style="text-align: center">Konsep</th>
            <th style="text-align: center">Periksa</th>
            <th style="text-align: center">Tolak</th>
            <th style="text-align: center">Setuju</th>
        </tr>
        </thead>
        <?php $date = $rekapHarianForm->getDate();?>
        <?php $end = $date->format('t'); ?>
        <?php for ($j = 1; $j <= $end; $j++) { ?>
            <?php $day = sprintf('%02d', $j)?>
            <?php $tanggal = $date->format("Y-m-$day");?>
            <tr class="tanggal tanggal-<?= $date->format("Y-m-$j");?>" data-tanggal="<?= $date->format("Y-m-$j");?>" style="cursor:pointer;" data-tanggal="<?= $tanggal;?>">
                <td style="text-align: center"><?= Helper::getTanggalSingkat($tanggal);?></td>
                <td style="text-align: center"><?= Helper::getHari($tanggal);?></td>
                <td style="text-align: center">
                    <?php
                        $teks = $pegawai->getCountKegiatanHarianV2([
                            'tanggal' => $tanggal,
                            'id_kegiatan_status' => KegiatanStatus::KONSEP,
                            'id_kegiatan_tahunan_versi' => 1,
                        ]);
                        $link = Html::a($teks,[
                            '/kinerja/kegiatan-harian/index',
                            'KegiatanHarianSearch[tanggal]'=>$tanggal,
                            'KegiatanHarianSearch[id_kegiatan_status]'=>KegiatanStatus::KONSEP
                        ],[
                            //'class'=>'label label-info',
                            'data-toggle'=>'tooltip',
                            'title'=>'Jumlah Kegiatan Konsep'
                        ]);

                        echo $link;
                    ?>
                </td>
                <td style="text-align: center">
                    <?php
                        $teks = $pegawai->getCountKegiatanHarianV2([
                            'tanggal' => $tanggal,
                            'id_kegiatan_status' => KegiatanStatus::PERIKSA,
                            'id_kegiatan_tahunan_versi' => 1,
                        ]);

                        $link = Html::a($teks,[
                            '/kinerja/kegiatan-harian/index',
                            'KegiatanHarianSearch[tanggal]'=>$tanggal,
                            'KegiatanHarianSearch[id_kegiatan_status]'=>KegiatanStatus::PERIKSA
                        ],[
                            //'class'=>'label label-warning',
                            'data-toggle'=>'tooltip',
                            'title'=>'Jumlah Kegiatan Periksa'
                        ]);

                        echo $link;
                    ?>
                </td>
                <td style="text-align: center">
                    <?php
                        $teks = $pegawai->getCountKegiatanHarianV2([
                            'tanggal' => $tanggal,
                            'id_kegiatan_status' => KegiatanStatus::TOLAK,
                            'id_kegiatan_tahunan_versi' => 1,
                        ]);

                        $link = Html::a($teks,[
                            '/kinerja/kegiatan-harian/index',
                            'KegiatanHarianSearch[tanggal]'=>$tanggal,
                            'KegiatanHarianSearch[id_kegiatan_status]'=>KegiatanStatus::TOLAK
                        ],[
                            //'class'=>'label label-danger',
                            'data-toggle'=>'tooltip',
                            'title'=>'Jumlah Kegiatan Tolak'
                        ]);

                        echo $link;
                    ?>
                </td>
                <td style="text-align: center">
                    <?php
                        $teks = $pegawai->getCountKegiatanHarianV2([
                            'tanggal' => $tanggal,
                            'id_kegiatan_status'=>KegiatanStatus::SETUJU,
                            'id_kegiatan_tahunan_versi' => 1,
                        ]);

                        $link = Html::a($teks,[
                            '/kinerja/kegiatan-harian/index',
                            'KegiatanHarianSearch[tanggal]'=>$tanggal,
                            'KegiatanHarianSearch[id_kegiatan_status]'=>KegiatanStatus::SETUJU
                        ],[
                            //'class'=>'label label-success',
                            'data-toggle'=>'tooltip',
                            'title'=>'Jumlah Kegiatan Setuju'
                        ]);

                        echo $link;
                    ?>
                </td>
                <td style="text-align: center">
                </td>
                <td style="text-align: center">&nbsp;</td>
                <td style="text-align: center">
                    <?= Html::a('<i class="fa fa-plus-square"></i>', [
                        '/kinerja/kegiatan-harian/create',
                        'id_kegiatan_harian_jenis' => KegiatanHarianJenis::KEGIATAN_SKP,
                        'tanggal' => $tanggal
                    ], [
                        'class' => 'link-tanggal',
                        'data-tanggal' => $tanggal,
                        'data-toggle' => 'tooltip',
                        'title' => 'Input Kegiatan Harian SKP'
                    ]);?>
                    <?= Html::a('<i class="fa fa-plus-square-o"></i>', [
                        '/kinerja/kegiatan-harian/create',
                        'id_kegiatan_harian_jenis' => KegiatanHarianJenis::KEGIATAN_TAMBAHAN,
                        'tanggal' => $tanggal
                    ], [
                        'class' => 'link-tanggal',
                        'data-tanggal' => $tanggal,
                        'data-toggle' => 'tooltip',
                        'title' => 'Input Kegiatan Harian Tambahan'
                    ]);?>
                </td>
            </tr>
        <?php } //END FOR ?>
        </table>
    </div>
</div>
