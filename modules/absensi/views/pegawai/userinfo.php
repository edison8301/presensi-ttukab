<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\Helper;
use yii\widgets\DetailView;
use app\modules\absensi\models\Pegawai;

/* @var $this yii\web\View */
/* @var $model app\models\Pegawai */

$this->title = "Detail Pegawai";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="pegawai-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Pegawai</h3>
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
                'attribute' => 'nip',
                'format' => 'raw',
                'value' => $model->nip,
            ],
            [
                'attribute' => 'id_instansi',
                'format' => 'raw',
                'value' => @$model->instansi->nama,
            ],
            [
                'attribute' => 'nama_jabatan',
                'format' => 'raw',
                'value' => $model->nama_jabatan,
            ],
            [
                'attribute' => 'id_atasan',
                'format' => 'raw',
                'value' => @$model->atasan->nama,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?php if(Pegawai::accessIndex()) { ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Pegawai', ['index'], ['class' => 'btn btn-primary btn-flat']) ?>
        <?php } ?>
    </div>
</div>


<div class="pegawai-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Terdaftar Pada Mesin</h3>
    </div>
    <div class="box-body">
        <table class="table table-bordered table-hover">
        <thead>
        <tr>
            <th style="text-align: center; vertical-align: middle; width: 60px">No</th>
            <th style="text-align: center; vertical-align: middle;">Userid</th>
            <th style="text-align: center; vertical-align: middle;">Badgenumber</th>
            <th style="text-align: center; vertical-align: middle;">Serialnumber</th>
            <th style="text-align: center; vertical-align: middle;">Template<br>Fingerprint</th>
            <th style="text-align: center; vertical-align: middle;">SKPD</th>
        </tr>
        </thead>
        <?php $i=1; foreach($model->getManyUserinfo()->all() as $data) { ?>
        <tr>
            <td style="text-align: center"><?= $i; ?></td>
            <td style="text-align: center"><?= $data->userid; ?></td>
            <td style="text-align: center"><?= $data->badgenumber; ?></td>
            <td style="text-align: center"><?= $data->SN; ?></td>
            <td style="text-align: center"><?= $data->countTemplate(); ?></td>
        </tr>
        <?php $i++; } ?>
        </table>

    </div>
</div>