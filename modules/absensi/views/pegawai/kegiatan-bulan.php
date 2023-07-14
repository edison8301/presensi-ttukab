<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\widgets\LabelKegiatan;
use app\components\Helper;
use app\models\User;

/* @var $this yii\web\View */

$this->title = 'Daftar Kegiatan Bulan '.Helper::getBulanSingkat(User::getBulan()).' '.User::getTahun();
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary">
    <div class="box-header">
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Kegiatan', ['kegiatan/create'], ['class' => 'btn btn-success btn-flat']); ?>
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
                'attribute' => 'namaKegiatan',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'label'=>'Bulan',
                'attribute' => 'bulan',
                'format' => 'raw',
                'value'=>function($data) {
                    return Helper::getBulanSingkat($data->bulan);
                },
                'headerOptions' => ['style' => 'text-align:center; width: 80px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'label'=>'Target',
                'format' => 'raw',
                'value'=>function($data) {
                    return $data->target_kuantitas.' '.$data->satuanKuantitas;
                },
                'headerOptions' => ['style' => 'text-align:center; width: 80px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'label'=>'Realisasi',
                'format' => 'raw',
                'value'=>function($data) {
                    return '0 '.$data->satuanKuantitas;
                },
                'headerOptions' => ['style' => 'text-align:center; width: 80px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'label'=>'% Realisasi',
                'format' => 'raw',
                'value'=>function($data) {
                    return '0%';
                },
                'headerOptions' => ['style' => 'text-align:center; width: 80px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            /*
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
            */
        ],
    ]); ?>
    </div>
</div>
