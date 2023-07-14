<?php

use app\components\Helper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\tukin\models\Jabatan */

$this->title = "Detail Jabatan";
$this->params['breadcrumbs'][] = ['label' => 'Jabatan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jabatan-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Jabatan</h3>
    </div>

    <div class="box-body">

    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            [
                'attribute' => 'id_jenis_jabatan',
                'format' => 'raw',
                'value' => $model->getJenisJabatan(),
            ],
            [
                'attribute' => 'nama',
                'format' => 'raw',
                'value' => $model->nama,
            ],
            [
                'attribute' => 'id_instansi',
                'format' => 'raw',
                'value' => @$model->instansi->nama,
            ],
            [
                'attribute' => 'bidang',
                'format' => 'raw',
                'value' => $model->bidang,
            ],
            [
                'attribute' => 'subbidang',
                'format' => 'raw',
                'value' => $model->subbidang,
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
                'attribute' => 'status_jumlah_tetap',
                'format' => 'raw',
                'value' => $model->getIsJumlahTetap() ? 'Aktif' : 'Tidak Aktif'
            ],
            [
                'attribute' => 'jumlah_tetap',
                'value' => Helper::rp($model->jumlah_tetap),
                'visible' => $model->getIsJumlahTetap(),
            ]
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Jabatan', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Jabatan', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
