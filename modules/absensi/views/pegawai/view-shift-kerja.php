<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\Helper;
use yii\widgets\DetailView;
use app\models\Pegawai;
use app\modules\absensi\models\Absensi;
use app\modules\absensi\models\KetidakhadiranJenis;

/* @var $this yii\web\View */
/* @var $pegawai app\models\Pegawai */

$this->title = "Detail Pegawai";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>


<div class="pegawai-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Data Pegawai</h3>
    </div>

    <div class="box-body">

    <?= DetailView::widget([
        'model' => $pegawai,
        'template' => '<tr><th style="text-align:left">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            [
                'attribute' => 'nama',
                'format' => 'raw',
                'value' => Html::encode($pegawai->nama),
            ],
            [
                'attribute' => 'nip',
                'format' => 'raw',
                'value' => Html::encode($pegawai->nip),
            ],
            [
                'attribute' => 'id_instansi',
                'format' => 'raw',
                'value' => Html::encode(@$pegawai->getNamaInstansi()),
            ],
            [
                'attribute' => 'nama_jabatan',
                'format' => 'raw',
                'value' => Html::encode(@$pegawai->instansiPegawaiBerlaku->nama_jabatan),
            ],
            [
                'label'=>'Shift Kerja',
                'value'=>Html::encode($pegawai->getNamaShiftKerja())
            ]
        ],
    ]) ?>

    </div>
</div>

<div class="pegawai-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Daftar Shift Kerja</h3>
    </div>
    <div class="box-header">
        <?php if($pegawai->accessCreatePegawaiShiftKerja()) { ?>
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Shift Kerja', ['/absensi/pegawai-shift-kerja/create','id_pegawai' => $pegawai->id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?php } ?>
    </div>

    <div class="box-body table-responsive">
        <table class="table table-bordered table-hover">
        <thead>
        <tr>
            <th style="text-align: center; vertical-align: middle; width: 60px">No</th>
            <th style="text-align: center; vertical-align: middle;">Shift Kerja</th>
            <th style="text-align: center; vertical-align: middle; width: 200px">Tanggal Berlaku</th>
            <th style="width: 60px">&nbsp;</th>
        </tr>
        </thead>
        <?php $i=1; foreach($pegawai->getManyPegawaiShiftKerja()->all() as $pegawaiShiftKerja) { ?>
        <tr>
            <td style="text-align: center"><?= $i; ?></td>
            <td style="text-align: left"><?= @$pegawaiShiftKerja->shiftKerja->nama; ?></td>
            <td style="text-align: center"><?= Helper::getTanggalSingkat($pegawaiShiftKerja->tanggal_berlaku); ?></td>
            <td style="text-align: center">
                <?= Html::a('<i class="fa fa-pencil"></i>',['/absensi/pegawai-shift-kerja/update','id'=>$pegawaiShiftKerja->id]); ?>
                <?= Html::a('<i class="fa fa-trash"></i>',['/absensi/pegawai-shift-kerja/delete','id'=>$pegawaiShiftKerja->id],['onclick'=>'return confirm("Yakin akan menghapus shift kerja?");']); ?>
            </td>
        </tr>
        <?php $i++; } ?>
        </table>

    </div>
</div>
