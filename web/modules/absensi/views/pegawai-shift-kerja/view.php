<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\PegawaiShiftKerja */

$this->title = "Detail Pegawai Shift Kerja";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Shift Kerja', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-shift-kerja-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Pegawai Shift Kerja</h3>
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
                'attribute' => 'id_shift_kerja',
                'format' => 'raw',
                'value' => $model->id_shift_kerja,
            ],
            [
                'attribute' => 'tanggal_berlaku',
                'format' => 'raw',
                'value' => $model->tanggal_berlaku,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Pegawai Shift Kerja', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Pegawai Shift Kerja', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
