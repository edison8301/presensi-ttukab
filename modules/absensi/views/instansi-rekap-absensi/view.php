<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\InstansiRekapAbsensi */

$this->title = "Detail Instansi Rekap Absensi";
$this->params['breadcrumbs'][] = ['label' => 'Instansi Rekap Absensi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="instansi-rekap-absensi-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Instansi Rekap Absensi</h3>
    </div>

    <div class="box-body">

    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            [
                'attribute' => 'id',
                'format' => 'raw',
                'value' => $model->id,
            ],
            [
                'attribute' => 'id_instansi',
                'format' => 'raw',
                'value' => $model->id_instansi,
            ],
            [
                'attribute' => 'bulan',
                'format' => 'raw',
                'value' => $model->bulan,
            ],
            [
                'attribute' => 'tahun',
                'format' => 'raw',
                'value' => $model->tahun,
            ],
            [
                'attribute' => 'persen_potongan_total',
                'format' => 'raw',
                'value' => $model->persen_potongan_total,
            ],
            [
                'attribute' => 'persen_potongan_fingerprint',
                'format' => 'raw',
                'value' => $model->persen_potongan_fingerprint,
            ],
            [
                'attribute' => 'persen_potongan_kegiatan',
                'format' => 'raw',
                'value' => $model->persen_potongan_kegiatan,
            ],
            [
                'attribute' => 'waktu_diperbarui',
                'format' => 'raw',
                'value' => $model->waktu_diperbarui,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Instansi Rekap Absensi', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Instansi Rekap Absensi', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
