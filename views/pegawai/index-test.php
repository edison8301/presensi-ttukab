<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PegawaiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Pegawai';

if($searchModel->mode == 'bawahan')
    $this->title .= ' Bawahan';

$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_search-index',[
    'action'=>['index-test'],
    'searchModel'=>$searchModel
]); ?>

<div class="pegawai-index box box-primary">

    <div class="box-header">
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Pegawai', ['create'], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('<i class="fa fa-print"></i> Export Excel Pegawai', Yii::$app->request->url.'&export=1', ['class' => 'btn btn-primary btn-flat']) ?>
    </div>

    <div class="box-body">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pager' => [
            'firstPageLabel' => 'First',
            'lastPageLabel'  => 'Last'
        ],
        //'perfectScrollbar'=>true,
        'responsiveWrap' => true,
        'hover'=>true,
        'striped'=>false,
        //'tableOptions'=>['class'=>'table-responsive'],
        'responsive'=>true,
        //'floatHeader'=>true,
        //'perfectScrollbar'=>true,
        //'floatOverflowContainer'=>true,
        'floatHeaderOptions'=>['scrollingTop'=>'0'],
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header' => 'No',
                'headerOptions' => ['style' => 'text-align:center'],
                'contentOptions' => ['style' => 'text-align:center']
            ],
            [
                'attribute' => 'nama',
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::encode($data['id_pegawai']);
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'text-align:center;width:80px']
            ],
        ],
    ]); ?>
    </div>
</div>
