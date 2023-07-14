<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\JabatanEvjab */

$this->title = "Detail Jabatan Evjab";
$this->params['breadcrumbs'][] = ['label' => 'Jabatan Evjab', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jabatan-evjab-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Jabatan Evjab</h3>
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
                'attribute' => 'id_jenis_jabatan',
                'format' => 'raw',
                'value' => $model->id_jenis_jabatan,
            ],
            [
                'attribute' => 'id_instansi',
                'format' => 'raw',
                'value' => $model->id_instansi,
            ],
            [
                'attribute' => 'id_instansi_bidang',
                'format' => 'raw',
                'value' => $model->id_instansi_bidang,
            ],
            [
                'attribute' => 'nama',
                'format' => 'raw',
                'value' => $model->nama,
            ],
            [
                'attribute' => 'nilai_jabatan',
                'format' => 'raw',
                'value' => $model->nilai_jabatan,
            ],
            [
                'attribute' => 'kelas_jabatan',
                'format' => 'raw',
                'value' => $model->kelas_jabatan,
            ],
            [
                'attribute' => 'persediaan_pegawai',
                'format' => 'raw',
                'value' => $model->persediaan_pegawai,
            ],
            [
                'attribute' => 'id_induk',
                'format' => 'raw',
                'value' => $model->id_induk,
            ],
            [
                'attribute' => 'status_hapus',
                'format' => 'raw',
                'value' => $model->status_hapus,
            ],
            [
                'attribute' => 'waktu_hapus',
                'format' => 'raw',
                'value' => $model->waktu_hapus,
            ],
            [
                'attribute' => 'id_user_hapus',
                'format' => 'raw',
                'value' => $model->id_user_hapus,
            ],
            [
                'attribute' => 'nomor',
                'format' => 'raw',
                'value' => $model->nomor,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Jabatan Evjab', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Jabatan Evjab', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
