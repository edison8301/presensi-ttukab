<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\widgets\LabelKegiatan;
use app\components\Helper;
use app\models\User;

/* @var $this yii\web\View */

$this->title = 'Daftar Kegiatan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary">
    <div class="box-header">
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Kegiatan Harian', [
            'kegiatan-realisasi/create',
            'kode_instansi'=>User::getKodeInstansi(),
            'kode_pegawai'=>User::getKodePegawai()
        ], ['class' => 'btn btn-success btn-flat']); ?>
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
                'label'=>'Tanggal',
                'attribute' => 'tanggal',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center; width: 80px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'namaKegiatan',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'text-align:center;width:80px'],
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('<i class="glyphicon glyphicon-eye-open"></i>', ['kegiatan-realisasi/view', 'id' => $key], ['data-toggle' => 'tooltip', 'title' => 'Detail']);
                    },
                    'update' => function ($url, $model, $key) {
                        return Html::a('<i class="glyphicon glyphicon-pencil"></i>', ['kegiatan-realisasi/update', 'id' => $key], ['data-toggle' => 'tooltip', 'title' => 'Ubah']);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<i class="glyphicon glyphicon-trash"></i>', ['kegiatan-realisasi/delete', 'id' => $key], ['data' => ['toggle' => 'tooltip', 'method' => 'post', 'confirm' => 'Yakin akan menghapus kegiatan?'], 'title' => 'Hapus']);
                    }
                ],
            ],
        ],
    ]); ?>
    </div>
</div>
