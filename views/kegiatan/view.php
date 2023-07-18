<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Kegiatan */

$this->title = "Detail Kegiatan";
$this->params['breadcrumbs'][] = ['label' => 'Kegiatan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kegiatan-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Kegiatan</h3>
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
                'attribute' => 'tanggal',
                'format' => 'raw',
                'value' => $model->tanggal,
            ],
            [
                'attribute' => 'jam_mulai_absen',
                'format' => 'raw',
                'value' => $model->jam_mulai_absen,
            ],
            [
                'attribute' => 'jam_selesai_absen',
                'format' => 'raw',
                'value' => $model->jam_selesai_absen,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Kegiatan', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Kegiatan', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>

<div class="kegiatan-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Daftar Kehadiran</h3>
    </div>

    <div class="box-body">
        <table class="table table-bordered">
            <tr>
                <th style="text-align: center; width: 50px;">No</th>
                <th>Nama</th>
                <th style="text-align: center; width: 200px;">Waktu Absen</th>
            </tr>
            <?php $no=1; foreach ($model->findAllPegawai() as $checkinout) { ?>
            <tr>
                <td style="text-align: center;">
                    <?= $no++; ?>
                </td>
                <td></td>
                <td style="text-align: center;">
                    <?php // $checkinout->checktime ?>
                </td>
            </tr>
        <?php } ?>
        </table>
    </div>

</div>
