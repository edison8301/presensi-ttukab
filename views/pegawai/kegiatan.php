<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\widgets\LabelKegiatan;

/* @var $this yii\web\View */

$this->title = 'Daftar Kegiatan Tahunan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary">
    <div class="box-header">
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Kegiatan Tahunan', ['kegiatan/create'], ['class' => 'btn btn-success btn-flat']); ?>
    </div>
    <div class="box-body">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header' => 'No',
                'headerOptions' => ['style' => 'text-align:center; width:60px'],
                'contentOptions' => ['style' => 'text-align:center']
            ],
            [
                'label'=>'Kode',
                'attribute' => 'kode_kegiatan',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center; width: 80px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'nama_kegiatan',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'attribute' => 'target_kuantitas',
                'format' => 'raw',
                'value'=>function($data) {
                    return $data->target_kuantitas.' '.$data->satuan_kuantitas;
                },
                'headerOptions' => ['style' => 'text-align:center; width: 80px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'target_waktu',
                'format' => 'raw',
                'value'=>function($data) {
                    return $data->target_waktu.' Bulan';
                },
                'headerOptions' => ['style' => 'text-align:center; width: 80px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'kode_kegiatan_status',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center; width: 80px'],
                'contentOptions' => ['style' => 'text-align:center;'],
                'value' => function ($data) {
                    return LabelKegiatan::widget([
                        'kegiatan' => $data
                    ]);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'text-align:center;width:80px'],
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('<i class="glyphicon glyphicon-eye-open"></i>', ['kegiatan/view', 'id' => $key], ['data-toggle' => 'tooltip', 'title' => 'Detail']);
                    },
                    'update' => function ($url, $model, $key) {
                        return Html::a('<i class="glyphicon glyphicon-pencil"></i>', ['kegiatan/update', 'id' => $key], ['data-toggle' => 'tooltip', 'title' => 'Ubah']);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<i class="glyphicon glyphicon-trash"></i>', ['kegiatan/delete', 'id' => $key], ['data' => ['toggle' => 'tooltip', 'method' => 'post', 'confirm' => 'Yakin akan menghapus kegiatan?'], 'title' => 'Hapus']);
                    }
                ],
            ],
        ],
    ]); ?>
    </div>
</div>
