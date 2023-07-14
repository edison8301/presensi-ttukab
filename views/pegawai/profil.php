<?php

use app\components\Helper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Pegawai */

$this->title = "Detail Pegawai";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

if(isset($debug)==false) {
    $debug = false;
}

?>
<div class="pegawai-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Pegawai</h3>
    </div>

    <div class="box-body">
    <?=DetailView::widget([
        'model' => $model,
        'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            [
                'attribute' => 'nama',
                'format' => 'raw',
                'value' => Html::encode($model->nama),
            ],
            [
                'attribute' => 'nip',
                'format' => 'raw',
                'value' => Html::encode($model->nip),
            ],
            /*
            [
                'attribute' => 'id_instansi',
                'format' => 'raw',
                'value' => Html::encode(@$model->instansi->nama),
            ],

            [
                'label' => 'Jabatan',
                'format' => 'raw',
                'value' => Html::encode(@$model->jabatan->nama),
            ],

            [
                'label' => 'Atasan',
                'format' => 'raw',
                'value' => @$model->atasan->nama.' ('.@$model->atasan->nip.')',
            ],
            */

            /*
            [
                'label'=>'Atasan',
                'value'=>@$model->getNamaJabatanAtasan().' ('.$model->getNamaPegawaiAtasan().')'
            ],
            */
            [
                'attribute' => 'telepon',
                'format' => 'raw',
                'value' => Html::encode($model->telepon),
            ],
            [
                'attribute' => 'email',
                'format' => 'raw',
                'value' => Html::encode($model->email),
            ],
        ],
    ])?>
    </div>

</div>

<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title">
            Riwayat Perangkat Daerah / Jabatan / Mutasi / Promosi Pegawai
        </h3>
    </div>
    <div class="box-body">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th style="text-align: center; width: 50px">No</th>
                <th style="text-align: center;">Jabatan</th>
                <th style="text-align: center;">Instansi</th>
                <th style="text-align: center;">Atasan</th>
                <th style="text-align: center; width: 150px">Tanggal<br/>TMT</th>
                <?php //if($debug==true) { ?>
                <th style="text-align: center; width: 125px">Tanggal<br/>Mulai Efektif</th>
                <th style="text-align: center; width: 125px">Tanggal<br/>Selesai Efektif</th>
                <?php //} ?>
            </tr>
            </thead>
        <?php $i = 1; ?>
        <?php foreach ($model->allInstansiPegawai as $instansiPegawai): ?>
            <tr>
                <td style="text-align: center;"><?= $i++; ?></td>
                <td style="text-transform: uppercase"><?= $instansiPegawai->getNamaJabatan(); ?></td>
                <td style="text-align: center;"><?= @$instansiPegawai->instansi->nama; ?></td>
                <td style="text-align: center;"><?= @$instansiPegawai->jabatanAtasan->nama; ?></td>
                <td style="text-align: center;"><?= Helper::getTanggalSingkat($instansiPegawai->tanggal_berlaku); ?></td>
                <?php //if($debug==1) { ?>
                <td style="text-align: center;"><?= Helper::getTanggalSingkat($instansiPegawai->tanggal_mulai); ?></td>
                <td style="text-align: center;"><?= Helper::getTanggalSingkat($instansiPegawai->tanggal_selesai); ?></td>
                <?php ///} ?>
            </tr>
        <?php endforeach?>
        </table>
    </div>
</div>
