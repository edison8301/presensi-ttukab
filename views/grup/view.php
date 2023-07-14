<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use app\models\Grup;

/* @var $this yii\web\View */
/* @var $model app\models\Grup */

$this->title = "Detail Grup";
$this->params['breadcrumbs'][] = ['label' => 'Grup', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grup-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Grup</h3>
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
                'attribute' => 'id_instansi',
                'format' => 'raw',
                'value' => @$model->instansi->nama,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?php if($model->accessUpdate()) { ?>
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Grup', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?php } ?>
        <?php if(Grup::accessIndex()) { ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Grup', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
        <?php } ?>
    </div>

</div>


<div class="box box-success">
    <div class="box-header">
        <h3 class="box-title">Daftar Pegawai</h3>
    </div>
    <div class="box-header">
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Pegawai', ['grup-pegawai/create', 'id_grup' => $model->id], ['class' => 'btn btn-primary btn-flat']); ?>
    </div>
    <div class="box-body">
        <table class="table table-hover table-striped table-condensed">
            <thead>
                <tr>
                    <th width="90px" style="text-align: center;">No</th>
                    <th style="text-align: center;">Pegawai</th>
                    <th width="120px" style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <?php $i = 1; ?>
            <?php foreach ($model->manyGrupPegawai as $grupPegawai): ?>
                <tr>
                    <td style="text-align: center;"> <?= $i++; ?> </td>
                    <td>
                        <?= @$grupPegawai->pegawai->nama; ?>
                    </td>
                    <td style="text-align: center;">
                        <?= Html::a('<i class="fa fa-eye"></i>', ['absensi/pegawai/view-shift-kerja', 'id' => $grupPegawai->id_pegawai], ['data-toggle' => 'tooltip', 'title' => 'Lihat Shift Kerja']); ?>
                        <?= Html::a('<i class="fa fa-pencil"></i>', ['grup-pegawai/update', 'id' => $grupPegawai->id], ['data-toggle' => 'tooltip', 'title' => 'Edit']); ?>
                        <?= Html::a('<i class="fa fa-trash"></i>', ['grup-pegawai/hapus', 'id' => $grupPegawai->id], ['data' => ['method' => 'post', 'confirm' => 'Yakin akan menghapus data?', 'toggle' => 'tooltip'], 'title' => 'Hapus']); ?>
                    </td>
                </tr>
            <?php endforeach ?>
        </table>
        <?php /*
        <?= GridView::widget([
            'dataProvider' => $grupSearchModel,
            'filterModel' => $grupDataProvider,
            'columns' => [
                [
                    'class' => 'yii\grid\SerialColumn',
                    'header' => 'No',
                    'headerOptions' => ['style' => 'text-align:center; width:60px;vertical-align:middle'],
                    'contentOptions' => ['style' => 'text-align:center']
                ],
                'id',
                'id_pegawai',
                'id_grup',
                [
                    'format'=>'raw',
                    'value'=>function($data) {
                        $output  = '';
                        $output .= Html::a('<i class="fa fa-eye"></i>',['view-shift-kerja','id'=>$data->id],['data-toggle'=>'tooltip','title'=>'Lihat Shift Kerja']);
                        return trim($output);
                    },
                    'headerOptions'=>['style'=>'text-align:center;width:50px;vertical-align:middle'],
                    'contentOptions'=>['style'=>'text-align:center;'],
                ]
            ],
        ]); ?>
        */ ?>
    </div>
</div>
