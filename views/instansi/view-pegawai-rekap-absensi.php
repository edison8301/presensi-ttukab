<?php

use app\components\Helper;
use app\models\Instansi;
use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Instansi */
/* @var $searchModel \app\models\InstansiSearch */

$this->title = "Struktur Jabatan ".$searchModel->getBulanLengkapTahun();
$this->params['breadcrumbs'][] = ['label' => 'Instansi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('//filter/_filter-tahun'); ?>

<?= $this->render('_filter-view-jabatan',[
        'searchModel'=>$searchModel,
        'action'=>[
            '/instansi/view-jabatan',
            'id'=>$model->id
        ]
]); ?>

<div class="instansi-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Instansi</h3>
    </div>

	<div class="box-body">

    <?= DetailView::widget([
        'model' => $model,
        'template'=>'<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
    		[
            	'label'=>'Nama',
            	'format'=>'raw',
                'value'=>$model->nama
            ],
            [
                'label'=>'Singkatan',
                'format'=>'raw',
                'value'=>$model->singkatan
            ],
        ],
    ]) ?>
    </div>
</div>

<div class="instansi-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Struktur Jabatan</h3>
        <div class="box-tools">
            <button class="btn btn-sm btn-primary btn-flat" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>

    <div class="box-body">

        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th style="width: 50px; text-align: center">No</th>
                <th style="text-align: center; vertical-align: middle">Nama Pegawai</th>
                <th style="text-align: center; vertical-align: middle">Hari Kerja</th>
                <th style="text-align: center; vertical-align: middle">Hadir</th>
                <th style="text-align: center;">Tidak Hadir</th>
                <th style="text-align: center; width: 50px">Tanpa Keterangan</th>
                <th style="width: 80px;"></th>
            </tr>
            </thead>
            <?php $no=1; foreach($allInstansiPegawai as $instansiPegawai) { ?>
                <?php
                    $pegawaiRekapAbsensi = \app\modules\absensi\models\PegawaiRekapAbsensi::findOne([
                        'id_pegawai' => $instansiPegawai->id_pegawai,
                        'bulan' => $bulan,
                        'tahun' => $tahun
                    ]);
                ?>
            <tr>
                <td style="text-align: center"><?= $no; ?></td>
                <td>
                    <?= Html::a(@$instansiPegawai->pegawai->nama,[
                        '/absensi/pegawai/view',
                        'id' => $instansiPegawai->id_pegawai,
                        'PegawaiSearch[bulan]' => $bulan
                    ]); ?>
                </td>
                <td style="text-align: center">
                    <?= @$pegawaiRekapAbsensi->jumlah_hari_kerja; ?>
                </td>
                <td style="text-align: center">
                    <?= @$pegawaiRekapAbsensi->jumlah_hadir; ?>
                    (<?= Helper::persen(@$pegawaiRekapAbsensi->jumlah_hadir, @$pegawaiRekapAbsensi->jumlah_hari_kerja); ?>)
                </td>
                <td style="text-align: center">
                    <?= @$pegawaiRekapAbsensi->jumlah_tidak_hadir; ?>
                    (<?= Helper::persen(@$pegawaiRekapAbsensi->jumlah_tidak_hadir, @$pegawaiRekapAbsensi->jumlah_hari_kerja); ?>)
                </td>
                <td style="text-align: center">
                    <?= @$pegawaiRekapAbsensi->jumlah_tanpa_keterangan; ?>
                    (<?= Helper::persen(@$pegawaiRekapAbsensi->jumlah_tanpa_keterangan, @$pegawaiRekapAbsensi->jumlah_hari_kerja); ?>)
                </td>
                <td></td>
            </tr>
            <?php $no++; } ?>
        </table>
    </div>
</div>
