<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\tunjangan\models\JabatanTunjanganKhususPegawai */

$this->title = "Detail Jabatan Tunjangan Khusus Pegawai";
$this->params['breadcrumbs'][] = ['label' => 'Jabatan Tunjangan Khusus Pegawai', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jabatan-tunjangan-khusus-pegawai-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Jabatan Tunjangan Khusus Pegawai</h3>
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
                'attribute' => 'id_pegawai',
                'format' => 'raw',
                'value' => $model->id_pegawai,
            ],
            [
                'attribute' => 'id_instansi',
                'format' => 'raw',
                'value' => $model->id_instansi,
            ],
            [
                'attribute' => 'tanggal_mulai',
                'format' => 'raw',
                'value' => $model->tanggal_mulai,
            ],
            [
                'attribute' => 'tanggal_selesai',
                'format' => 'raw',
                'value' => $model->tanggal_selesai,
            ],
            [
                'attribute' => 'keterangan',
                'format' => 'raw',
                'value' => $model->keterangan,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Jabatan Tunjangan Khusus Pegawai', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Jabatan Tunjangan Khusus Pegawai', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
